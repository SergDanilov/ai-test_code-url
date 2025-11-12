@extends('layouts.app')

@section('content')
@auth
<div class="max-w-8xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Список проверок API</h1>
        <a href="{{ route('checks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Новая проверка
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Фильтр -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('checks.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="site" class="block text-sm font-medium text-gray-700 mb-2">Фильтр по сайту</label>
                <select name="site" id="site" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Все сайты</option>
                    @foreach($sites as $site)
                        <option value="{{ $site }}" {{ request('site') == $site ? 'selected' : '' }}>
                            {{ $site }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Применить
                </button>
                <a href="{{ route('checks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Сбросить
                </a>
            </div>
        </form>

        <!-- Статистика -->
        @if(request('site'))
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                <p class="text-sm text-blue-700">
                    Показаны проверки для сайта: <strong>{{ request('site') }}</strong>
                    <span class="text-blue-600">•</span>
                    Найдено проверок: {{ $checks->count() }}
                </p>
            </div>
        @endif
    </div>

    <!-- Десктопная таблица -->
    <div class="hidden md:block bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Название</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Сайт</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Оценка</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Дата</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($checks as $check)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $check->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ $check->site ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $check->url }}">
                                {{ $check->url }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($check->rating)
                                @php
                                    $rating = $check->rating;
                                    if ($rating == 'ОТЛИЧНО' || $rating == 'ХОРОШО') {
                                        $colorClass = 'bg-green-100 text-green-800';
                                        $icon = '✅';
                                    } elseif ($rating == 'УДОВЛЕТВОРИТЕЛЬНО') {
                                        $colorClass = 'bg-yellow-100 text-yellow-800';
                                        $icon = '⚠️';
                                    } elseif ($rating == 'ПЛОХО' || $rating == 'НЕУДОВЛЕТВОРИТЕЛЬНО') {
                                        $colorClass = 'bg-red-100 text-red-800';
                                        $icon = '❌';
                                    } else {
                                        $colorClass = 'bg-gray-100 text-gray-800';
                                        $icon = '❓';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ $icon }} {{ $rating }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($check->success)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Успешно
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Ошибка ({{ $check->status_code }})
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $check->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('checks.show', $check) }}" class="text-blue-600 hover:text-blue-900 whitespace-nowrap">Просмотр</a>
                                <form action="{{ route('checks.destroy', $check) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 whitespace-nowrap" onclick="return confirm('Удалить проверку?')">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            @if(request('site'))
                                Нет проверок для сайта "{{ request('site') }}"
                            @else
                                Нет выполненных проверок
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Мобильные карточки -->
    <div class="md:hidden space-y-4">
        @forelse($checks as $check)
        <div class="bg-white shadow-md rounded-lg p-4">
            <div class="space-y-3">
                <div>
                    <h3 class="font-medium text-gray-900">{{ $check->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $check->site ?? '—' }}</p>
                </div>

                <div class="text-sm">
                    <p class="text-gray-600 truncate" title="{{ $check->url }}">{{ $check->url }}</p>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        @if($check->rating)
                            @php
                                $rating = $check->rating;
                                if ($rating == 'ОТЛИЧНО' || $rating == 'ХОРОШО') {
                                    $colorClass = 'bg-green-100 text-green-800';
                                } elseif ($rating == 'УДОВЛЕТВОРИТЕЛЬНО') {
                                    $colorClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($rating == 'ПЛОХО' || $rating == 'НЕУДОВЛЕТВОРИТЕЛЬНО') {
                                    $colorClass = 'bg-red-100 text-red-800';
                                } else {
                                    $colorClass = 'bg-gray-100 text-gray-800';
                                }
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                Итог: {{ $rating }}
                            </span>
                        @endif
                        @if($check->success)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Статус проверки: успешно
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Статус проверки: ошибка ({{ $check->status_code }})
                            </span>
                        @endif
                    </div>
                    <span class="text-sm text-gray-500">{{ $check->created_at->format('d.m.Y H:i') }}</span>
                </div>

                <div class="flex space-x-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('checks.show', $check) }}" class="text-blue-600 hover:text-blue-900 text-sm">Просмотр</a>
                    <form action="{{ route('checks.destroy', $check) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Удалить проверку?')">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-gray-500">
                @if(request('site'))
                    Нет проверок для сайта "{{ request('site') }}"
                @else
                    Нет выполненных проверок
                @endif
            </p>
        </div>
        @endforelse
    </div>
</div>
@if($checks instanceof \Illuminate\Pagination\LengthAwarePaginator && $checks->hasPages())
    <div class="mt-6">
        {{ $checks->links() }}
    </div>
@endif
@else
    <div class="text-center py-12">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Доступ запрещен</h2>
        <p class="text-gray-600 mb-4">Для просмотра проверок необходимо авторизоваться</p>
        <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Войти
        </a>
    </div>
@endauth
@endsection