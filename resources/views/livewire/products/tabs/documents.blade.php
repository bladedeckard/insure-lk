<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Настройка документов</h2>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
        <h3 class="text-sm font-semibold text-yellow-800 mb-2">📋 Доступные переменные для шаблонов</h3>
        <p class="text-xs text-yellow-700 mb-3">Используйте <code>${variable}</code> в DOCX-шаблонах. Нажмите на переменную чтобы скопировать.</p>
        
        @php $vars = $this->getAvailableVariables(); @endphp
        @foreach($vars as $category => $categoryVars)
            @if(!empty($categoryVars))
                <div class="mb-3">
                    <h4 class="text-xs font-semibold text-yellow-800 mb-1">{{ $category }}</h4>
                    <div class="flex flex-wrap gap-1">
                        @foreach($categoryVars as $varCode => $varLabel)
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white rounded border border-yellow-200 text-xs cursor-pointer hover:bg-yellow-100"
                                onclick="navigator.clipboard.writeText('${{ $varCode }}')" title="${{ $varCode }}">
                                <code class="text-blue-600">{{ $varCode }}</code>
                                <span class="text-gray-400">— {{ $varLabel }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Шаблоны --}}
    @foreach([
        ['key' => 'policy', 'label' => 'Полис', 'prop' => 'policy_template', 'toggle' => 'use_policy'],
        ['key' => 'kid', 'label' => 'КИД (Ключевой Информационный Документ)', 'prop' => 'kid_template', 'toggle' => 'use_kid'],
        ['key' => 'application', 'label' => 'Заявление', 'prop' => 'application_template', 'toggle' => 'use_application'],
    ] as $docType)
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
                    <h3 class="font-medium text-gray-800">{{ $docType['label'] }}</h3>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="{{ $docType['toggle'] }}"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Использовать</span>
                    </label>
                </div>
            </div>

            @if(${$docType['toggle']})
                @php 
                    $existing = collect($documents)->firstWhere('type', $docType['key']);
                @endphp
                @if($existing)
                    <div class="flex items-center gap-2 text-sm text-green-600 mb-2">
                        <span>✅</span>
                        <span>{{ $existing['name'] }}</span>
                    </div>
                @endif
                <input type="file" wire:model="{{ $docType['prop'] }}" accept=".docx"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Формат: .docx с переменными ${variable}</p>
            @endif
        </div>
    @endforeach
</div>
