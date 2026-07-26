{{-- Модальное окно детализации расчёта --}}
@if($showCalcDetail && !empty($calculation['breakdown']))
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="backdrop-filter: blur(4px);"
     x-data @keydown.escape.window="$wire.set('showCalcDetail', false)">
    <div class="absolute inset-0 bg-black/50" wire:click="$set('showCalcDetail', false)"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-y-auto">

        {{-- Header --}}
        <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Детализация расчёта</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Все параметры, влияющие на премию</p>
                </div>
                <button wire:click="$set('showCalcDetail', false)" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="px-6 py-4 space-y-5">

            {{-- Общая информация --}}
            <div class="bg-slate-50 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-slate-700 mb-3">Общая информация</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-slate-500">ОСЗ (остаток задолженности):</span>
                        <span class="font-medium text-slate-800">{{ number_format($calculation['breakdown']['osg'] ?? 0, 0, '', ' ') }} ₽</span>
                    </div>
                    <div>
                        <span class="text-slate-500">Коэфф. ОСЗ банка:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['osg_coeff'] ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500">Страховая сумма:</span>
                        <span class="font-medium text-emerald-600">{{ number_format($calculation['breakdown']['insurance_sum'] ?? 0, 0, '', ' ') }} ₽</span>
                    </div>
                    <div>
                        <span class="text-slate-500">Банк:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['bank_code'] ?? '—' }}</span>
                    </div>
                    @if(!empty($calculation['breakdown']['bank_coefficient_property']))
                    <div>
                        <span class="text-slate-500">Коэфф. банка (имущество):</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['bank_coefficient_property'] }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="text-slate-500">Посредник:</span>
                        <span class="font-medium text-slate-800">{{ ($calculation['breakdown']['intermediary_coeff'] ?? 1) < 1 ? 'Да (КВ ' . round((1 - ($calculation['breakdown']['intermediary_coeff'] ?? 1)) * 100) . '%)' : 'Нет' }}</span>
                    </div>
                </div>
            </div>

            {{-- Несчастный случай (Жизнь) --}}
            @if(isset($calculation['breakdown']['life']))
            <div class="border border-slate-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Несчастный случай (жизнь)
                    </h4>
                    <span class="text-lg font-bold text-slate-900">{{ number_format($calculation['breakdown']['life'], 2, ',', ' ') }} ₽</span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Возраст:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['life_age'] ?? '—' }} лет</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Пол:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['life_sex'] ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Базовый тариф НС:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['life_base_tariff'] ?? '—' }}%</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Коэфф. банка (базовый):</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['life_bank_coeff'] ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Спорт:</span>
                        <span class="font-medium text-slate-800">{{ ($calculation['breakdown']['life_sport'] ?? 1) > 1 ? 'Экстремальный (' . $calculation['breakdown']['life_sport'] . ')' : 'Нет' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Деятельность:</span>
                        <span class="font-medium text-slate-800">{{ ($calculation['breakdown']['life_job'] ?? 1) > 1 ? 'Опасная (' . $calculation['breakdown']['life_job'] . ')' : 'Нерисковая' }}</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Базовый тариф:</span>
                            <span class="font-medium {{ ($calculation['breakdown']['life_re'] ?? 0) > ($calculation['breakdown']['life_std'] ?? 0) ? 'text-slate-400 line-through' : 'text-slate-800' }}">{{ $calculation['breakdown']['life_std'] ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Перестрахование:</span>
                            <span class="font-medium {{ ($calculation['breakdown']['life_re'] ?? 0) > ($calculation['breakdown']['life_std'] ?? 0) ? 'text-emerald-600' : 'text-slate-400 line-through' }}">{{ $calculation['breakdown']['life_re'] ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Тариф (выбран):</span>
                            <span class="font-medium text-slate-800">{{ $calculation['breakdown']['life_tariff'] ?? '—' }}%</span>
                        </div>
                        @if(!empty($calculation['breakdown']['life_eff_tariff']) && $calculation['breakdown']['life_eff_tariff'] != $calculation['breakdown']['life_tariff'])
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Тариф итого:</span>
                            <span class="font-medium text-amber-600">{{ $calculation['breakdown']['life_eff_tariff'] ?? '—' }}%</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200">
                            <span class="text-slate-700 font-semibold">Премия:</span>
                            <span class="text-sm font-bold text-emerald-600">{{ number_format($calculation['breakdown']['life'] ?? 0, 2, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Имущество --}}
            @if(isset($calculation['breakdown']['property']))
            <div class="border border-slate-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Имущество (конструктив)
                    </h4>
                    <span class="text-lg font-bold text-slate-900">{{ number_format($calculation['breakdown']['property'], 2, ',', ' ') }} ₽</span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Тип помещения:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['property_room'] ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Тип перекрытия:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['property_cover'] ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Возраст дома:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['property_house_age'] ?? '—' }} лет</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Базовый тариф:</span>
                            <span class="font-medium {{ ($calculation['breakdown']['property_re'] ?? 0) > ($calculation['breakdown']['property_std'] ?? 0) ? 'text-slate-400 line-through' : 'text-slate-800' }}">{{ $calculation['breakdown']['property_std'] ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Перестрахование:</span>
                            <span class="font-medium {{ ($calculation['breakdown']['property_re'] ?? 0) > ($calculation['breakdown']['property_std'] ?? 0) ? 'text-emerald-600' : 'text-slate-400 line-through' }}">{{ $calculation['breakdown']['property_re'] ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Тариф (выбран):</span>
                            <span class="font-medium text-slate-800">{{ $calculation['breakdown']['property_tariff'] ?? '—' }}%</span>
                        </div>
                        @if(!empty($calculation['breakdown']['property_eff_tariff']) && $calculation['breakdown']['property_eff_tariff'] != $calculation['breakdown']['property_tariff'])
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Тариф итого:</span>
                            <span class="font-medium text-amber-600">{{ $calculation['breakdown']['property_eff_tariff'] ?? '—' }}%</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200">
                            <span class="text-slate-700 font-semibold">Премия:</span>
                            <span class="text-sm font-bold text-emerald-600">{{ number_format($calculation['breakdown']['property'] ?? 0, 2, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Титул --}}
            @if(isset($calculation['breakdown']['title']))
            <div class="border border-slate-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        Титул
                    </h4>
                    <span class="text-lg font-bold text-slate-900">{{ number_format($calculation['breakdown']['title'], 2, ',', ' ') }} ₽</span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="mt-3 pt-3 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Базовый тариф:</span>
                            <span class="font-medium {{ ($calculation['breakdown']['title_re'] ?? 0) > ($calculation['breakdown']['title_std'] ?? 0) ? 'text-slate-400 line-through' : 'text-slate-800' }}">{{ $calculation['breakdown']['title_std'] ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Перестрахование:</span>
                            <span class="font-medium {{ ($calculation['breakdown']['title_re'] ?? 0) > ($calculation['breakdown']['title_std'] ?? 0) ? 'text-emerald-600' : 'text-slate-400 line-through' }}">{{ $calculation['breakdown']['title_re'] ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Тариф (выбран):</span>
                            <span class="font-medium text-slate-800">{{ $calculation['breakdown']['title_tariff'] ?? '—' }}%</span>
                        </div>
                        @if(!empty($calculation['breakdown']['title_eff_tariff']) && $calculation['breakdown']['title_eff_tariff'] != $calculation['breakdown']['title_tariff'])
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-slate-600 font-medium">Тариф итого:</span>
                            <span class="font-medium text-amber-600">{{ $calculation['breakdown']['title_eff_tariff'] ?? '—' }}%</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200">
                            <span class="text-slate-700 font-semibold">Премия:</span>
                            <span class="text-sm font-bold text-emerald-600">{{ number_format($calculation['breakdown']['title'] ?? 0, 2, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Коэффициенты --}}
            <div class="bg-slate-50 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-slate-700 mb-3">Коэффициенты</h4>
                <div class="space-y-2 text-sm">
                    @if(!empty($calculation['breakdown']['promo_coeff']) && $calculation['breakdown']['promo_coeff'] < 1)
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Промокод (скидка):</span>
                        <span class="font-medium text-emerald-600">-{{ round((1 - $calculation['breakdown']['promo_coeff']) * 100) }}%</span>
                    </div>
                    @endif
                    @if(!empty($calculation['breakdown']['markup_coeff']) && $calculation['breakdown']['markup_coeff'] > 1)
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Надбавка:</span>
                        <span class="font-medium text-amber-600">+{{ round(($calculation['breakdown']['markup_coeff'] - 1) * 100) }}%</span>
                    </div>
                    @endif
                    @if(!empty($calculation['breakdown']['intermediary_coeff']) && $calculation['breakdown']['intermediary_coeff'] < 1)
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Коэфф. посредника:</span>
                        <span class="font-medium text-slate-800">{{ $calculation['breakdown']['intermediary_coeff'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Итого --}}
            <div class="bg-primary-50 rounded-xl p-4 border border-primary-200">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold text-slate-900">Итого премия:</span>
                    <span class="text-2xl font-bold text-primary-600">{{ number_format($premium, 2, ',', ' ') }} ₽</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="sticky bottom-0 bg-white border-t border-slate-100 px-6 py-4 rounded-b-2xl">
            <button wire:click="$set('showCalcDetail', false)"
                class="w-full px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-sm font-medium transition-colors">
                Закрыть
            </button>
        </div>
    </div>
</div>
@endif
