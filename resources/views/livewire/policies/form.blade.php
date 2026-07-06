<div>
<h1 class="text-2xl font-semibold mb-4">Полис {{ $policy?->number ? '№ '.$policy->number : '(черновик)' }}</h1>
<div class="grid grid-cols-3 gap-6">
<div class="col-span-2 bg-white rounded border p-5 space-y-4">
<div>
<label class="text-sm">Страховой продукт</label>
<select wire:model.live="product_id" class="border rounded px-3 py-2 w-full">
<option value="">— выберите —</option>
@foreach($products as $pr)<option value="{{ $pr->id }}">{{ $pr->name }}</option>@endforeach
</select>
</div>

@if($product)
@if($product->code === 'property')
<h2 class="font-semibold">Об объекте страхования</h2>
<div class="grid grid-cols-2 gap-3 text-sm">
<div><label>Адрес помещения</label><input wire:model.blur="data.property_address" class="border rounded px-3 py-2 w-full" placeholder="Москва, ... кв. 1"></div>
<div><label>Площадь, м²</label><input type="number" wire:model.blur="data.area" class="border rounded px-3 py-2 w-full"></div>
<div><label>Конструктивные элементы, ₽</label><input type="number" wire:model.blur="data.sum_construct" class="border rounded px-3 py-2 w-full"></div>
<div><label>Отделка, ₽</label><input type="number" wire:model.blur="data.sum_finish" class="border rounded px-3 py-2 w-full" placeholder="700000"></div>
<div><label>Движимое имущество, ₽</label><input type="number" wire:model.blur="data.sum_movable" class="border rounded px-3 py-2 w-full"></div>
<div><label>ГО, ₽</label><input type="number" wire:model.blur="data.sum_go" class="border rounded px-3 py-2 w-full" placeholder="200000"></div>
<div><label class="flex items-center gap-2 mt-6"><input type="checkbox" wire:model.live="data.electricity"> Воздействие электроэнергии</label></div>
<div><label class="flex items-center gap-2 mt-6"><input type="checkbox" wire:model.live="data.is_rent"> Сдаётся в аренду?</label></div>
</div>
<div class="grid grid-cols-4 gap-3 text-sm">
<div><label>Замена ключей</label><select wire:model.live="data.exp_keys" class="border rounded px-2 py-2 w-full"><option value="0">0</option><option value="5000">5000</option><option value="10000">10000</option></select></div>
<div><label>Аренда помещения</label><select wire:model.live="data.exp_rent" class="border rounded px-2 py-2 w-full"><option value="0">0</option><option value="100000">100000</option></select></div>
<div><label>Транспортировка</label><select wire:model.live="data.exp_transport" class="border rounded px-2 py-2 w-full"><option value="0">0</option><option value="10000">10000</option></select></div>
<div><label>Досроч. возвращение</label><select wire:model.live="data.exp_return" class="border rounded px-2 py-2 w-full"><option value="0">0</option><option value="20000">20000</option></select></div>
</div>
<h2 class="font-semibold pt-2">Страхователь</h2>
<div class="grid grid-cols-3 gap-3 text-sm">
<input placeholder="Фамилия" wire:model.blur="data.last_name" class="border rounded px-3 py-2">
<input placeholder="Имя" wire:model.blur="data.first_name" class="border rounded px-3 py-2">
<input placeholder="Отчество" wire:model.blur="data.middle_name" class="border rounded px-3 py-2">
<input placeholder="Дата рождения" type="date" wire:model.blur="data.birth_date" class="border rounded px-3 py-2">
<input placeholder="Серия паспорта ХХ ХХ" wire:model.blur="data.passport_series" class="border rounded px-3 py-2">
<input placeholder="Номер паспорта" wire:model.blur="data.passport_number" class="border rounded px-3 py-2">
<input placeholder="Телефон" wire:model.blur="data.phone" class="border rounded px-3 py-2">
<input placeholder="Email" wire:model.blur="data.email" type="email" class="border rounded px-3 py-2 col-span-2">
</div>
<div><label>Дата начала страхования</label><input type="date" wire:model.blur="data.start_date" class="border rounded px-3 py-2"></div>

@elseif($product->code === 'mortgage')
<h2 class="font-semibold">Ипотека – Новосел</h2>
<div class="grid grid-cols-3 gap-3 text-sm">
<div><label>Банк</label><select wire:model.live="data.bank" class="border rounded px-3 py-2 w-full"><option value="sber">Сбербанк</option><option value="vtb">ВТБ</option><option value="alfa">Альфабанк</option></select></div>
<div><label>ОСЗ</label><input type="number" wire:model.blur="data.osg" class="border rounded px-3 py-2 w-full"></div>
<div><label>Коэф. ОСЗ банка</label><input type="number" step="0.1" wire:model.blur="data.bank_osg_coeff" class="border rounded px-3 py-2 w-full" value="1"></div>
</div>
<div class="text-sm">Риски: 
<label><input type="checkbox" wire:model.live="data.risks" value="property"> Имущество</label>
<label class="ml-3"><input type="checkbox" wire:model.live="data.risks" value="life"> Жизнь</label>
<label class="ml-3"><input type="checkbox" wire:model.live="data.risks" value="title"> Титул</label>
</div>
<div class="grid grid-cols-3 gap-3 text-sm">
<div><label>Тип помещения</label><select wire:model.live="data.room_type" class="border rounded px-3 py-2 w-full"><option value="apartment">Квартира</option><option value="house">Дом</option><option value="non_res">Нежилое</option><option value="land">Земельный участок</option></select></div>
<div><label>Перекрытия</label><select wire:model.live="data.cover_type" class="border rounded px-3 py-2 w-full"><option value="stone">Каменный</option><option value="mixed">Смешанный</option><option value="wood">Деревянный</option></select></div>
<div><label>Возраст дома</label><input type="number" wire:model.blur="data.house_age" class="border rounded px-3 py-2 w-full"></div>
<div><label>Дата рождения</label><input type="date" wire:model.blur="data.birth_date" class="border rounded px-3 py-2 w-full"></div>
<div><label>Пол</label><select wire:model.live="data.sex" class="border rounded px-3 py-2 w-full"><option value="m">М</option><option value="f">Ж</option></select></div>
<div><label>Промокод</label><input wire:model.blur="data.promocode" class="border rounded px-3 py-2 w-full"></div>
</div>
<div class="text-sm"><label><input type="checkbox" wire:model.live="data.extreme_sport"> Экстремальный спорт</label>
<label class="ml-3"><input type="checkbox" wire:model.live="data.danger_job"> Опасная деятельность</label></div>
<div class="grid grid-cols-2 gap-3 text-sm">
<input placeholder="Email" wire:model.blur="data.email" class="border rounded px-3 py-2">
<input placeholder="Телефон" wire:model.blur="data.phone" class="border rounded px-3 py-2">
<input placeholder="Дата начала" type="date" wire:model.blur="data.start_date" class="border rounded px-3 py-2">
</div>
@else
<div class="text-sm text-slate-500">Динамическая форма по config_json продукта — не реализована в демо, используйте JSON-редактор. Введите данные в массив data вручную через продукт-конструктор.</div>
<textarea wire:model="data" rows="6" class="w-full border font-mono text-xs"></textarea>
@endif

@if(!empty($calculation['errors']))
<div class="text-rose-700 bg-rose-50 border border-rose-200 rounded p-2 text-sm">
@foreach($calculation['errors'] as $f=>$m)<div>{{ $f }}: {{ $m }}</div>@endforeach
</div>
@endif
@endif
<div class="pt-2 flex gap-2">
<button wire:click="saveDraft" class="border px-4 py-2 rounded">Сохранить черновик</button>
<button wire:click="issue" class="bg-emerald-600 text-white px-4 py-2 rounded">Выпустить полис</button>
</div>
</div>

<div class="bg-white rounded border p-5 h-fit">
<h3 class="font-semibold mb-2">Расчёт</h3>
<div class="text-2xl font-bold">{{ number_format($premium,2,',',' ') }} ₽</div>
<pre class="text-xs bg-slate-50 p-2 rounded mt-2 overflow-auto">{{ json_encode($calculation['breakdown'] ?? [], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre>
@if(!empty($calculation['needs_approval']))<div class="mt-2 text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 text-sm">Требуется согласование (>10 млн / титул)</div>@endif
<hr class="my-3">
<input placeholder="Email страхователя" wire:model="policyholder_email" class="border rounded px-3 py-2 w-full text-sm mb-2">
<input placeholder="Телефон" wire:model="policyholder_phone" class="border rounded px-3 py-2 w-full text-sm">
<textarea placeholder="Комментарий" wire:model="comment" class="border rounded px-3 py-2 w-full text-sm mt-2"></textarea>
</div>
</div>
</div>
