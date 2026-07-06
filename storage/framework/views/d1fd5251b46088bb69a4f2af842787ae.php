<div>
<h1 class="text-2xl font-semibold mb-4">Посредник</h1>
<div class="bg-white rounded border p-5 max-w-2xl space-y-3">
<div class="flex gap-2 items-end">
<div class="flex-1"><label class="text-sm">ИНН</label><input wire:model="inn" class="border rounded px-3 py-2 w-full"></div>
<button wire:click="dadataLookup" class="border px-3 py-2 rounded h-[42px]">Найти в DaData</button>
</div>
<div><label class="text-sm">Наименование</label><input wire:model="name" class="border rounded px-3 py-2 w-full"></div>
<div class="grid grid-cols-2 gap-3">
<div><label class="text-sm">Номер агентского договора</label><input wire:model="contract_number" class="border rounded px-3 py-2 w-full"></div>
<div><label class="text-sm">Статус</label>
<select wire:model="type" class="border rounded px-3 py-2 w-full"><option value="legal">Юридическое лицо</option><option value="ip">ИП</option><option value="individual">Физическое лицо</option></select>
</div>
</div>
<div><label class="flex items-center gap-2"><input type="checkbox" wire:model="is_active"> Действующий</label>
<p class="text-xs text-slate-500">Если выключено — агенты этого посредника не смогут оформлять полисы.</p></div>
<div><label class="text-sm">Комментарий</label><textarea wire:model="comment" class="border rounded px-3 py-2 w-full"></textarea></div>
<div class="flex gap-2"><button wire:click="save" class="bg-slate-900 text-white px-4 py-2 rounded">Сохранить</button><a href="<?php echo e(route('intermediaries.index')); ?>" class="px-4 py-2">Отмена</a></div>
</div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/intermediaries/form.blade.php ENDPATH**/ ?>