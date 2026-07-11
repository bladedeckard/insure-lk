<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Лог изменений и версии</h2>

    {{-- Версии --}}
    @if(!empty($versions))
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">📦 Версии продукта</h3>
            <div class="space-y-2">
                @foreach($versions as $version)
                    <div class="flex items-center justify-between bg-white rounded p-3 border">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-sm font-semibold">v{{ $version['version'] }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full
                                {{ $version['status'] === 'published' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $version['status'] === 'draft' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $version['status'] === 'archived' ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                {{ $version['status'] }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $version['created_at'] }}</span>
                            @if($version['change_note'])
                                <span class="text-xs text-gray-500">{{ $version['change_note'] }}</span>
                            @endif
                        </div>
                        @if($version['status'] !== 'published')
                            <button wire:click="rollbackToVersion({{ $version['version'] }})"
                                onclick="return confirm('Откатить к версии {{ $version['version'] }}?')"
                                class="px-3 py-1 text-xs bg-orange-100 text-orange-700 rounded hover:bg-orange-200">
                                Откатить
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Лог --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 mb-3">📝 История изменений</h3>
        @if(empty($versionLogs))
            <p class="text-gray-400 text-sm text-center py-6">Лог пока пуст</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-3 text-gray-500 font-medium">Действие</th>
                            <th class="text-left py-2 px-3 text-gray-500 font-medium">Пользователь</th>
                            <th class="text-left py-2 px-3 text-gray-500 font-medium">Дата</th>
                            <th class="text-left py-2 px-3 text-gray-500 font-medium">Детали</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($versionLogs as $log)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-3">
                                    <span class="px-2 py-0.5 rounded text-xs
                                        {{ $log['action'] === 'Опубликован' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $log['action'] === 'Обновлён' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $log['action'] === 'Откат к версии' ? 'bg-orange-100 text-orange-700' : '' }}
                                        {{ $log['action'] === 'Создан' ? 'bg-purple-100 text-purple-700' : '' }}
                                    ">
                                        {{ $log['action'] }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-gray-600">{{ $log['user'] }}</td>
                                <td class="py-2 px-3 text-gray-400 text-xs">{{ $log['created_at'] }}</td>
                                <td class="py-2 px-3 text-gray-400 text-xs">
                                    @if($log['diff'])
                                        <code>{{ json_encode($log['diff'], JSON_UNESCAPED_UNICODE) }}</code>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
