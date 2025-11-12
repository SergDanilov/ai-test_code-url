@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Детали проверки</h1>
        <a href="{{ route('checks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Назад к списку
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Название</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $check->name }}</p>
            </div>
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Сайт</h3>
                <p class="text-sm text-gray-900 font-medium">{{ $check->site ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Статус</h3>
                <p class="mt-1">
                    @if($check->success)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Успешно
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Ошибка
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">HTTP код</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $check->status_code }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Дата выполнения</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $check->created_at->format('d.m.Y H:i:s') }}</p>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500 mb-2">URL</h3>
            <p class="text-sm text-gray-900 break-all">{{ $check->url }}</p>
        </div>

        @if($check->tz)
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500 mb-2">TZ параметр</h3>
            <p class="text-sm text-gray-900">{{ $check->tz }}</p>
        </div>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Ответ от API</h3>

        @if(isset($parsedData))
            <!-- Структурированный вид -->
            <div class="mb-4 space-x-2">
                <button onclick="showView('structured')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                    Структурированный вид
                </button>
                <button onclick="showView('json')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                    Форматированный JSON
                </button>
                <button onclick="showView('raw')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                    Исходный ответ
                </button>
            </div>

            <!-- Структурированный вид -->
            <div id="structuredView" class="view">
                @include('checks.partials.structured-view', ['data' => $parsedData])
            </div>

            <!-- Форматированный JSON -->
            <div id="jsonView" class="view hidden">
                <pre class="text-sm text-gray-800 whitespace-pre-wrap overflow-x-auto bg-gray-100 p-4 rounded-lg"><code class="language-json">{{ $formattedResponse }}</code></pre>
            </div>

            <!-- Исходный ответ -->
            <div id="rawView" class="view hidden">
                <pre class="text-sm text-gray-800 whitespace-pre-wrap overflow-x-auto bg-gray-100 p-4 rounded-lg">{{ $check->response }}</pre>
            </div>
        @else
            <!-- Простой вывод если не удалось распарсить -->
            <div class="bg-gray-100 p-4 rounded-lg">
                <pre class="text-sm text-gray-800 whitespace-pre-wrap overflow-x-auto">{{ $check->response }}</pre>
            </div>
        @endif
    </div>
</div>

<script>
function showView(viewName) {
    // Скрыть все вью
    document.querySelectorAll('.view').forEach(view => {
        view.classList.add('hidden');
    });

    // Показать выбранное вью
    document.getElementById(viewName + 'View').classList.remove('hidden');
}

// По умолчанию показываем структурированный вид
document.addEventListener('DOMContentLoaded', function() {
    showView('structured');
});
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection