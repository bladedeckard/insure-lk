<div>
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('dictionaries.index') }}" class="text-slate-500 hover:text-slate-800">← К списку словарей</a>
        <h1 class="text-2xl font-semibold">
            {{ $dictionary ? 'Словарь: '.$dictionary->name : 'Новый словарь' }}
        </h1>
    </div>

    @if(session('ok')) <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded mb-4">{{ session('ok') }}</div> @endif
    @if(session('err')) <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2 rounded mb-4">{{ session('err') }}</div> @endif

    <div class="grid grid-cols-3 gap-6">
        {{-- Левая колонка: карточка словаря + элементы --}}
        <div class="col-span-2 space-y-6">
            {{-- Карточка словаря --}}
            <div class="bg-white rounded border p-5">
                <h2 class="font-semibold mb-3">Основные данные словаря</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Код (техническое имя) *</label>
                        <input type="text" wire:model="code" class="border rounded px-3 py-2 w-full font-mono text-sm" placeholder="banks">
                        @error('code') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
                        <p class="text-xs text-slate-500 mt-1">Латиница, без пробелов. Используется в коде: <code>Dictionary::where('code','banks')</code></p>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Название *</label>
                        <input type="text" wire:model="name" class="border rounded px-3 py-2 w-full" placeholder="Банки">
                        @error('name') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <label class="text-sm text-slate-600">Meta (JSON, опционально)</label>
                    <textarea wire:model="meta" rows="2" class="border rounded px-3 py-2 w-full font-mono text-xs" placeholder="{}"></textarea>
                    @error('meta') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
                </div>
                <div class="mt-3">
                    <button wire:click="saveDictionary" class="bg-slate-900 text-white px-4 py-2 rounded">Сохранить словарь</button>
                    @if(!$dictionary)
                        <span class="text-xs text-slate-500 ml-3">Сначала сохраните словарь, потом добавляйте элементы</span>
                    @endif
                </div>
            </div>

            {{-- Элементы --}}
            @if($dictionary)
            <div class="bg-white rounded border p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold">Элементы словаря ({{ $items->count() }})</h2>
                    @if($item_id)
                        <button wire:click="resetItemForm" class="text-sm text-slate-600">+ Новый элемент</button>
                    @endif
                </div>

                {{-- Форма элемента --}}
                <div class="border rounded p-3 bg-slate-50 mb-4">
                    <div class="text-sm font-medium mb-2">{{ $item_id ? 'Редактирование элемента' : 'Новый элемент' }}</div>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div>
                            <label>Ключ *</label>
                            <input type="text" wire:model="item_key" class="border rounded px-2 py-1.5 w-full font-mono" placeholder="sber">
                            @error('item_key') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label>Название *</label>
                            <input type="text" wire:model="item_label" class="border rounded px-2 py-1.5 w-full" placeholder="Сбербанк">
                            @error('item_label') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label>Сортировка</label>
                                <input type="number" wire:model="item_sort" class="border rounded px-2 py-1.5 w-full">
                            </div>
                            <label class="flex items-center gap-2 pt-5">
                                <input type="checkbox" wire:model="item_is_active"> Активен
                            </label>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label>data — дополнительные данные (JSON)</label>
                        <textarea wire:model="item_data" rows="4" class="border rounded px-2 py-1.5 w-full font-mono text-xs" placeholder='{"key": "value"}'></textarea>
                        @error('item_data') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
                        <p class="text-xs text-slate-500">Валидный JSON-объект. Оставьте <code>{}</code> если доп. данных нет.</p>
                    </div>
                    <div class="mt-2">
                        <button wire:click="saveItem" class="bg-emerald-600 text-white px-3 py-1.5 rounded text-sm">Сохранить элемент</button>
                        @if($item_id)
                            <button wire:click="resetItemForm" class="px-3 py-1.5 text-sm">Отмена</button>
                        @endif
                    </div>
                </div>

                {{-- Таблица элементов --}}
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left p-2">Ключ</th>
                            <th class="text-left p-2">Название</th>
                            <th class="text-left p-2">data</th>
                            <th class="text-center p-2 w-16">Активен</th>
                            <th class="text-right p-2 w-32"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $it)
                        <tr class="border-t hover:bg-slate-50">
                            <td class="p-2 font-mono text-xs">{{ $it->key }}</td>
                            <td class="p-2">{{ $it->label }}</td>
                            <td class="p-2 text-xs text-slate-600 max-w-[320px]"><pre class="whitespace-pre-wrap break-all">{{ json_encode($it->data, JSON_UNESCAPED_UNICODE) }}</pre></td>
                            <td class="p-2 text-center">{{ $it->is_active ? '✓' : '—' }}</td>
                            <td class="p-2 text-right space-x-2 text-xs">
                                <button wire:click="editItem({{ $it->id }})" class="text-blue-600">Править</button>
                                <button onclick="confirmAction({
                                    type: 'danger',
                                    title: 'Удаление элемента',
                                    message: 'Удалить элемент «{{ $it->label }}»?',
                                    confirmText: 'Удалить',
                                    onConfirm: function() { @this.call('deleteItem', {{ $it->id }}); }
                                })" class="text-rose-600">Удалить</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-4 text-center text-slate-500">Элементов пока нет</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 rounded p-4 text-sm text-amber-900">
                Сначала сохраните словарь, после этого появится блок управления элементами.
            </div>
            @endif
        </div>

        {{-- Правая колонка: помощь --}}
        <div class="space-y-4">
            <div class="bg-white rounded border p-4 text-sm">
                <b>Что такое Словарь?</b>
                <p class="text-slate-600 mt-1">
                    Универсальный справочник вида <b>код → список элементов</b>.<br>
                    Используется в страховых продуктах, в виджетах, в калькуляторах.
                </p>
                <div class="mt-3 text-xs space-y-1 text-slate-600">
                    <div><b>code</b> — техническое имя, латиница. Пример: <code>banks</code>, <code>regions</code></div>
                    <div><b>Название</b> — человеческое, на русском</div>
                    <div><b>meta</b> — общие настройки словаря в JSON, обычно пусто <code>{}</code></div>
                </div>
            </div>

            <div class="bg-slate-50 border rounded p-4 text-sm">
                <b>Поле <code>data</code> в элементе — что это?</b>
                <p class="text-slate-600 mt-2">
                Это произвольный JSON-объект с дополнительными параметрами элемента. Какие именно ключи класть — зависит от того, где словарь используется.
                </p>
                <p class="text-slate-600 mt-2"><b>Пример: словарь "Банки" для ипотеки "Новосел"</b></p>
                <pre class="text-xs bg-white border rounded p-2 mt-1 overflow-auto">{
  "commission": 0,
  "constructive": true,
  "bank_coeff": 40,
  "osg_coeff": 1
}</pre>
                <ul class="text-xs text-slate-600 mt-2 space-y-1 list-disc pl-4">
                    <li><code>commission</code> — комиссия банка, %</li>
                    <li><code>constructive</code> — учитываются ли конструктивные элементы (true/false)</li>
                    <li><code>bank_coeff</code> — коэффициент банка для расчёта премии (40, 50, 70…)</li>
                    <li><code>osg_coeff</code> — коэффициент остатка ссудной задолженности</li>
                </ul>
                <p class="text-slate-600 mt-2 text-xs">
                Калькулятор в продукте <code>MortgageCalculator</code> читает эти поля так:<br>
                <code>$bank = DictionaryItem::where('dictionary.code','banks')->where('key',$input['bank'])->first();<br>$k_bank = $bank->data['bank_coeff'] ?? 50;</code>
                </p>
                <hr class="my-3">
                <p class="text-xs text-slate-600">
                <b>Как добавлять свои?</b><br>
                1. Придумайте код словаря, например <code>property_types</code><br>
                2. Добавьте элементы: key=<code>apartment</code>, label=<code>Квартира</code>, data=<code>{"coeff":1}</code><br>
                3. В калькуляторе продукта читайте: <code>Dictionary::where('code','property_types')...</code><br><br>
                Если доп. данных нет — оставляйте <code>{}</code>.
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded p-3 text-xs text-blue-900">
                Подсказка: поле <code>key</code> должно быть уникальным внутри словаря. Именно по нему идёт поиск в коде.<br>
                <code>label</code> — то что видит пользователь в выпадающем списке.
            </div>
        </div>
    </div>
</div>
