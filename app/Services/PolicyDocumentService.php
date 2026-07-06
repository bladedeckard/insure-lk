<?php

namespace App\Services;

use App\Models\Policy;
use App\Mail\PolicyIssuedMail;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\TemplateProcessor;

class PolicyDocumentService
{
    public function issue(Policy $policy)
    {
        $product = $policy->product;
        $config = $product->config_json ?? [];
        $template = $config['template'] ?? null;
        $templatePath = storage_path('app/templates/'.($template ?? 'policy_default.docx'));
        if (!file_exists($templatePath)) {
            // создаём минимальный шаблон на лету
            @mkdir(dirname($templatePath), 0777, true);
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $section->addText('Полис № ${number}');
            $section->addText('Продукт: ${product}');
            $section->addText('Страхователь: ${fio}');
            $section->addText('Премия: ${premium} руб.');
            $section->addText('Период: ${start} - ${end}');
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($templatePath);
        }

        $tp = new TemplateProcessor($templatePath);
        $data = $policy->data_json ?? [];
        $fio = trim(($data['last_name'] ?? '').' '.($data['first_name'] ?? '').' '.($data['middle_name'] ?? ''));
        $tp->setValue('number', $policy->number ?? 'DRAFT');
        $tp->setValue('product', $product->name);
        $tp->setValue('fio', $fio ?: 'Страхователь');
        $tp->setValue('premium', number_format($policy->premium, 2, '.', ' '));
        $tp->setValue('start', optional($policy->start_date)->format('d.m.Y') ?? '');
        $tp->setValue('end', optional($policy->end_date)->format('d.m.Y') ?? '');

        $outDir = storage_path('app/policies');
        @mkdir($outDir, 0777, true);
        $outPath = $outDir.'/'.$policy->number.'.docx';
        $tp->saveAs($outPath);

        if ($policy->policyholder_email) {
            try {
                Mail::to($policy->policyholder_email)->send(new PolicyIssuedMail($policy, $outPath));
            } catch (\Throwable $e) { \Log::warning('Mail failed: '.$e->getMessage()); }
        }
        return $outPath;
    }
}
