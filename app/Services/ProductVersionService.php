<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVersion;
use App\Models\ProductVersionLog;
use Illuminate\Support\Facades\Auth;

/**
 * Сервис версионирования продуктов.
 * Создаёт снапшоты, публикует, архивирует, откатывает.
 */
class ProductVersionService
{
    /**
     * Создать снапшот текущей версии продукта.
     */
    public function createSnapshot(Product $product, string $changeNote = ''): ProductVersion
    {
        $snapshot = $this->buildSnapshot($product);
        
        $version = ProductVersion::create([
            'product_id' => $product->id,
            'version' => $product->current_version + 1,
            'status' => 'draft',
            'snapshot' => $snapshot,
            'created_by' => Auth::id(),
            'change_note' => $changeNote,
        ]);

        $this->log($product, 'updated', ['version' => $version->version, 'note' => $changeNote]);

        return $version;
    }

    /**
     * Опубликовать продукт (создать версию и перевести в published).
     */
    public function publish(Product $product, string $changeNote = ''): ProductVersion
    {
        // Архивируем все предыдущие published версии
        ProductVersion::where('product_id', $product->id)
            ->where('status', 'published')
            ->update(['status' => 'archived']);

        $snapshot = $this->buildSnapshot($product);

        $version = ProductVersion::create([
            'product_id' => $product->id,
            'version' => $product->current_version + 1,
            'status' => 'published',
            'snapshot' => $snapshot,
            'created_by' => Auth::id(),
            'change_note' => $changeNote,
        ]);

        $product->update([
            'status' => 'published',
            'current_version' => $version->version,
        ]);

        $this->log($product, 'published', ['version' => $version->version]);

        return $version;
    }

    /**
     * Откатить к указанной версии.
     */
    public function rollback(Product $product, int $versionNumber): void
    {
        $version = ProductVersion::where('product_id', $product->id)
            ->where('version', $versionNumber)
            ->firstOrFail();

        $snapshot = $version->snapshot;
        $this->restoreFromSnapshot($product, $snapshot);

        $product->update([
            'current_version' => $versionNumber,
            'status' => 'draft',
        ]);

        $this->log($product, 'rollback', ['from' => $product->current_version, 'to' => $versionNumber]);
    }

    /**
     * Построить полный снапшот продукта.
     */
    private function buildSnapshot(Product $product): array
    {
        $product->load([
            'coverages', 'fieldGroups', 'fields', 'restrictions.conditions',
            'documents', 'agreements', 'declarations', 'intermediaries'
        ]);

        return [
            'product' => $product->only([
                'code', 'name', 'marketing_name', 'description',
                'numerator_id', 'calculator_class', 'config_json',
                'formula_expression', 'formula_variables',
                'currency', 'is_active',
                'period_start_days', 'period_end_value', 'period_end_unit',
                'send_email', 'email_field', 'allow_edit_start_date', 'approval_emails',
            ]),
            'intermediaries' => $product->intermediaries->pluck('id')->toArray(),
            'coverages' => $product->coverages->map(fn($c) => $c->only([
                'name', 'code', 'type', 'min_value', 'max_value', 'default_value',
                'set_values', 'required_for_calc', 'sort_order', 'risks'
            ]))->toArray(),
            'field_groups' => $product->fieldGroups->map(fn($g) => $g->only([
                'name', 'code', 'description', 'sort_order'
            ]))->toArray(),
            'fields' => $product->fields->map(fn($f) => $f->only([
                'group_id', 'name', 'code', 'type', 'required', 'description', 'hint',
                'mask', 'regex', 'error_message', 'options', 'validation_rules',
                'visibility_condition', 'linked_to', 'sort_order'
            ]))->toArray(),
            'restrictions' => $product->restrictions->map(fn($r) => [
                'category' => $r->category,
                'action' => $r->action,
                'error_message' => $r->error_message,
                'logic' => $r->logic,
                'sort_order' => $r->sort_order,
                'conditions' => $r->conditions->map(fn($c) => $c->only([
                    'field_code', 'operator', 'value', 'group_id', 'sort_order'
                ]))->toArray(),
            ])->toArray(),
            'documents' => $product->documents->map(fn($d) => $d->only([
                'type', 'name', 'file_path', 'is_enabled', 'apply_conditions', 'sort_order'
            ]))->toArray(),
            'agreements' => $product->agreements->map(fn($a) => $a->only([
                'text', 'required', 'sort_order'
            ]))->toArray(),
            'declarations' => $product->declarations->map(fn($d) => $d->only([
                'name', 'text', 'is_active', 'required', 'show_conditions', 'sort_order'
            ]))->toArray(),
        ];
    }

    /**
     * Восстановить продукт из снапшота.
     */
    private function restoreFromSnapshot(Product $product, array $snapshot): void
    {
        // Удаляем все связанные данные
        $product->coverages()->delete();
        $product->fieldGroups()->delete();
        $product->fields()->delete();
        $product->restrictions()->delete(); // cascade удалит conditions
        $product->documents()->delete();
        $product->agreements()->delete();
        $product->declarations()->delete();
        $product->intermediaries()->detach();

        // Восстанавливаем основные поля
        $product->fill($snapshot['product']);
        $product->save();

        // Посредники
        if (!empty($snapshot['intermediaries'])) {
            $product->intermediaries()->attach($snapshot['intermediaries']);
        }

        // Покрытия
        foreach ($snapshot['coverages'] as $c) {
            $product->coverages()->create($c);
        }

        // Группы полей
        $groupMap = [];
        foreach ($snapshot['field_groups'] as $idx => $g) {
            $group = $product->fieldGroups()->create($g);
            $groupMap[$idx] = $group->id;
        }

        // Поля
        foreach ($snapshot['fields'] as $f) {
            if (isset($f['group_id']) && isset($groupMap[$f['group_id']])) {
                $f['group_id'] = $groupMap[$f['group_id']];
            }
            $product->fields()->create($f);
        }

        // Ограничения
        foreach ($snapshot['restrictions'] as $r) {
            $conditions = $r['conditions'] ?? [];
            unset($r['conditions']);
            $restriction = $product->restrictions()->create($r);
            foreach ($conditions as $cond) {
                $restriction->conditions()->create($cond);
            }
        }

        // Документы
        foreach ($snapshot['documents'] as $d) {
            $product->documents()->create($d);
        }

        // Соглашения
        foreach ($snapshot['agreements'] as $a) {
            $product->agreements()->create($a);
        }

        // Декларации
        foreach ($snapshot['declarations'] as $d) {
            $product->declarations()->create($d);
        }
    }

    /**
     * Записать действие в лог.
     */
    private function log(Product $product, string $action, array $diff = []): void
    {
        ProductVersionLog::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'diff' => $diff,
        ]);
    }
}
