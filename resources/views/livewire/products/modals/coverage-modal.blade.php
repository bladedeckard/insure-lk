{{-- Coverage Modal --}}
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    {{ $editingCoverageIndex >= 0 ? 'Редактировать покрытие' : 'Новое покрытие' }}
                </h3>
                <button wire:click="$set('showCoverageModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Название *</label>
                        <input type="text" wire:model.defer="cov_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Конструктивные элементы">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Код переменной
                            <span class="text-gray-400 text-xs">(для формулы)</span>
                        </label>
                        <input type="text" wire:model.defer="cov_code"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="sum_construct">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Тип покрытия</label>
                    <select wire:model="cov_type"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="range">Диапазон (min — max — по умолчанию)</option>
                        <option value="constant">Константа</option>
                        <option value="set">Множество значений (выпадающий список)</option>
                        <option value="flag">Флаг (Да/Нет)</option>
                    </select>
                </div>

                {{-- Range fields --}}
                @if($cov_type === 'range')
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Минимум</label>
                            <input type="number" wire:model.defer="cov_min_value"
                                class="w-full border border-gray-300 rounded px-3 py-2" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Максимум</label>
                            <input type="number" wire:model.defer="cov_max_value"
                                class="w-full border border-gray-300 rounded px-3 py-2" placeholder="2000000">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">По умолчанию</label>
                            <input type="number" wire:model.defer="cov_default_value"
                                class="w-full border border-gray-300 rounded px-3 py-2" placeholder="0">
                        </div>
                    </div>
                @endif

                @if($cov_type === 'constant')
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Значение</label>
                        <input type="number" wire:model.defer="cov_default_value"
                            class="w-full border border-gray-300 rounded px-3 py-2" placeholder="100000">
                    </div>
                @endif

                @if($cov_type === 'set')
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Значения (через запятую)</label>
                        <input type="text" wire:model.defer="cov_set_values"
                            class="w-full border border-gray-300 rounded px-3 py-2"
                            placeholder="0, 5000, 10000">
                    </div>
                @endif

                @if($cov_type === 'flag')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Описание
                            <span class="text-gray-400 text-xs">(отображается в форме полиса)</span>
                        </label>
                        <textarea wire:model.defer="cov_description" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Страхование от несчастных случаев и болезней"></textarea>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Риски (через запятую)
                    </label>
                    <textarea wire:model.defer="cov_risks" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Пожар, Удар молнии, Повреждение водой, Стихийные бедствия"></textarea>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.defer="cov_required_for_calc"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Обязательно для расчёта</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button wire:click="$set('showCoverageModal', false)"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Отмена</button>
                <button wire:click="saveCoverage"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Сохранить</button>
            </div>
        </div>
    </div>
</div>
