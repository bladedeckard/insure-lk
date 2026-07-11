<div class="min-h-screen bg-gray-50">
    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $productId ? 'Редактирование продукта' : 'Новый продукт' }}
                    </h1>
                    <p class="text-gray-600 mt-1">Конструктор страховых продуктов</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        Отмена
                    </a>
                    <button wire:click="saveDraft" class="px-6 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        Сохранить черновик
                    </button>
                    <button wire:click="saveAndPublish" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Опубликовать
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div class="bg-white rounded-t-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto">
                    @foreach ($tabs as $key => $label)
                        <button
                            wire:click="setTab('{{ $key }}')"
                            class="px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                                {{ $activeTab === $key 
                                    ? 'border-blue-500 text-blue-600' 
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="bg-white rounded-b-lg shadow p-6">
            
            {{-- TAB 1: Basic Information --}}
            @if ($activeTab === 'basic')
                @include('livewire.products.tabs.basic', [
                    'currencies' => $currencies,
                    'intermediaries' => $intermediaries
                ])
            @endif

            {{-- TAB 2: Coverages --}}
            @if ($activeTab === 'coverages')
                @include('livewire.products.tabs.coverages')
            @endif

            {{-- TAB 3: Formula --}}
            @if ($activeTab === 'formula')
                @include('livewire.products.tabs.formula')
            @endif

            {{-- TAB 4: Order Settings --}}
            @if ($activeTab === 'order')
                @include('livewire.products.tabs.order', ['numerators' => $numerators])
            @endif

            {{-- TAB 5: Fields --}}
            @if ($activeTab === 'fields')
                @include('livewire.products.tabs.fields', ['fieldTypes' => $fieldTypes])
            @endif

            {{-- TAB 6: Documents --}}
            @if ($activeTab === 'documents')
                @include('livewire.products.tabs.documents')
            @endif

            {{-- TAB 7: Advanced --}}
            @if ($activeTab === 'advanced')
                @include('livewire.products.tabs.advanced', ['operators' => $operators])
            @endif

            {{-- TAB 8: Log --}}
            @if ($activeTab === 'log')
                @include('livewire.products.tabs.log')
            @endif

        </div>
    </div>

    {{-- Coverage Modal --}}
    @if ($showCoverageModal)
        @include('livewire.products.modals.coverage-modal')
    @endif

    {{-- Field Modal --}}
    @if ($showFieldModal)
        @include('livewire.products.modals.field-modal', ['fieldTypes' => $fieldTypes])
    @endif
</div>
