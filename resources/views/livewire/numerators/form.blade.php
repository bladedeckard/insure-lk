<div>
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('numerators.index') }}" class="text-slate-500 hover:text-slate-800">← К списку нумераторов</a>
        <h1 class="text-2xl font-semibold">
            {{ $numerator ? 'Редактирование: '.$numerator->name : 'Новый нумератор' }}
        </h1>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 bg-white rounded border p-5 space-y-4">
            <div>
                <label class="text-sm text-slate-600">Название *</label>
                <input type="text" wire:model="name" class="border rounded px-3 py-2 w-full" placeholder="Полисы Имущество">
                @error('name') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Префикс (собственное значение)</label>
                    <input type="text" wire:model.live="prefix" class="border rounded px-3 py-2 w-full font-mono" placeholder="S380Z">
                    <p class="text-xs text-slate-500 mt-1">Например: S380Z</p>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Начальное значение счётчика</label>
                    <input type="number" wire:model.live="start_value" min="0" class="border rounded px-3 py-2 w-full">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <label class="flex items-center gap-2 pt-6">
                    <input type="checkbox" wire:model.live="include_year">
                    <span class="text-sm">Включать год</span>
                </label>
                <div>
                    <label class="text-sm text-slate-600">Формат года</label>
                    <select wire:model.live="year_digits" class="border rounded px-3 py-2 w-full" @disabled(!$include_year)>
                        <option value="2">2 цифры (26)</option>
                        <option value="4">4 цифры (2026)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Длина счётчика</label>
                    <input type="number" wire:model.live="counter_length" min="1" max="12" class="border rounded px-3 py-2 w-full">
                    <p class="text-xs text-slate-500">6 → 000001</p>
                </div>
            </div>

            <div>
                <label class="text-sm text-slate-600">Периодичность сброса счётчика</label>
                <select wire:model="reset_period" class="border rounded px-3 py-2 w-full max-w-xs">
                    <option value="never">Никогда</option>
                    <option value="yearly">Каждый год</option>
                </select>
            </div>

            <div class="flex gap-2 pt-2">
                <button wire:click="save" class="bg-slate-900 text-white px-5 py-2 rounded hover:bg-slate-800">Сохранить</button>
                <a href="{{ route('numerators.index') }}" class="px-4 py-2 border rounded hover:bg-slate-50">Отмена</a>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-slate-900 text-white rounded p-4">
                <div class="text-xs opacity-70">Предпросмотр номера</div>
                <div class="text-2xl font-mono mt-1 break-all">{{ $this->preview }}</div>
                <div class="text-xs opacity-70 mt-2">
                    Префикс: <b>{{ $prefix ?: '(пусто)' }}</b><br>
                    Год: {{ $include_year ? ($year_digits == 2 ? date('y') : date('Y')) : 'нет' }}<br>
                    Счётчик: {{ str_pad($start_value, $counter_length, '0', STR_PAD_LEFT) }}
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded p-3 text-xs text-amber-900">
                <b>Как работает нумератор:</b><br>
                Номер = <code>Префикс + Год + Счётчик</code><br><br>
                Пример из ТЗ:<br>
                Префикс <code>S380Z</code>, год 26, счётчик длиной 6, начало 1<br>
                → <b>S380Z26000001</b><br><br>
                Сброс "каждый год" — 1 января счётчик обнуляется до <code>start_value</code>.
            </div>
        </div>
    </div>
</div>
