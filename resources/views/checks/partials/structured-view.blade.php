<div class="space-y-6">
    @if(isset($data['отчет_о_анализе']))
        @php $report = $data['отчет_о_анализе']; @endphp

        <!-- Основная информация -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-lg mb-3">Основная информация</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-medium">Проект:</span>
                    <p class="mt-1">{{ $report['проект'] ?? 'Не указано' }}</p>
                </div>
                <div>
                    <span class="font-medium">Дата анализа:</span>
                    <p class="mt-1">{{ $report['дата_анализа'] ?? 'Не указано' }}</p>
                </div>
                <div>
                    <span class="font-medium">Инженер QA:</span>
                    <p class="mt-1">{{ $report['инженер_QA'] ?? 'Не указано' }}</p>
                </div>
                <div>
                    <span class="font-medium">Итоговая оценка:</span>
                    <p class="mt-1 font-semibold {{ $report['итоговая_оценка'] == 'УДОВЛЕТВОРИТЕЛЬНО' ? 'text-yellow-600' : 'text-green-600' }}">
                        {{ $report['итоговая_оценка'] ?? 'Не указано' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Результаты проверки ТЗ -->
        @if(isset($report['результаты']['проверка_ТЗ']))
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-lg mb-3">Проверка технического задания (ТЗ)</h4>
            <div class="space-y-3">
                @php
                    $pointDescriptions = [
                        '1 пункт' => 'Валидность HTML-кода (соответствие стандартам W3C)',
                        '2 пункт' => 'Корректность микроразметки (Schema.org, Open Graph)',
                        '3 пункт' => 'Адаптивная верстка (мобильная, планшетная версии)',
                        '4 пункт' => 'Оптимизация производительности (скорость загрузки, Core Web Vitals)',
                        '5 пункт' => 'Доступность (accessibility, WCAG guidelines)',
                        '6 пункт' => 'SEO-оптимизация (мета-теги, заголовки, семантика)',
                        '7 пункт' => 'Кроссбраузерность и кроссплатформенность'
                    ];
                @endphp

                @foreach($report['результаты']['проверка_ТЗ'] as $key => $value)
                    @if(!in_array($key, ['результат']) && isset($pointDescriptions[$key]))
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-gray-100">
                        <div class="mb-2 sm:mb-0">
                            <span class="font-medium">{{ $key }}:</span>
                            <p class="text-sm text-gray-600">{{ $pointDescriptions[$key] }}</p>
                        </div>
                        <span class="font-medium px-3 py-1 rounded
                            {{ $value == 'ВЫПОЛНЕНО' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $value == 'НЕВЫПОЛНЕНО' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $value == 'ЧАСТИЧНО ВЫПОЛНЕНО' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $value }}
                        </span>
                    </div>
                    @endif
                @endforeach

                @if(isset($report['результаты']['проверка_ТЗ']['результат']))
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
                    <span class="font-medium text-blue-800">Общий результат проверки ТЗ:</span>
                    <p class="mt-2 text-blue-700">{{ $report['результаты']['проверка_ТЗ']['результат'] }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Проверка адаптивности -->
        @if(isset($report['результаты']['проверка_адаптивности']))
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-lg mb-3">Проверка адаптивности</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                @if(isset($report['результаты']['проверка_адаптивности']['мобильная_верстка']))
                <div class="text-center p-3 border rounded-lg {{ $report['результаты']['проверка_адаптивности']['мобильная_верстка'] == 'КОРРЕКТНО' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <span class="font-medium block">Мобильная верстка</span>
                    <span class="{{ $report['результаты']['проверка_адаптивности']['мобильная_верстка'] == 'КОРРЕКТНО' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $report['результаты']['проверка_адаптивности']['мобильная_верстка'] }}
                    </span>
                </div>
                @endif

                @if(isset($report['результаты']['проверка_адаптивности']['планшетная_верстка']))
                <div class="text-center p-3 border rounded-lg {{ $report['результаты']['проверка_адаптивности']['планшетная_верстка'] == 'КОРРЕКТНО' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <span class="font-medium block">Планшетная верстка</span>
                    <span class="{{ $report['результаты']['проверка_адаптивности']['планшетная_верстка'] == 'КОРРЕКТНО' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $report['результаты']['проверка_адаптивности']['планшетная_верстка'] }}
                    </span>
                </div>
                @endif

                @if(isset($report['результаты']['проверка_адаптивности']['сетка_Bootstrap']))
                <div class="text-center p-3 border rounded-lg {{ $report['результаты']['проверка_адаптивности']['сетка_Bootstrap'] == 'КОРРЕКТНО' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <span class="font-medium block">Сетка Bootstrap</span>
                    <span class="{{ $report['результаты']['проверка_адаптивности']['сетка_Bootstrap'] == 'КОРРЕКТНО' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $report['результаты']['проверка_адаптивности']['сетка_Bootstrap'] }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Найденные проблемы -->
            @if(isset($report['результаты']['проверка_адаптивности']['найденные_проблемы']))
            <div>
                <h5 class="font-medium text-red-700 mb-2">Найденные проблемы:</h5>
                <ul class="list-disc list-inside space-y-2 text-sm">
                    @foreach($report['результаты']['проверка_адаптивности']['найденные_проблемы'] as $problem)
                    <li class="text-red-600">{{ $problem }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

        <!-- Дополнительные наблюдения -->
        @if(isset($report['результаты']['дополнительные_наблюдения']))
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-lg mb-3">Дополнительные наблюдения</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if(isset($report['результаты']['дополнительные_наблюдения']['производительность']))
                <div class="text-center p-4 border rounded-lg bg-orange-50">
                    <span class="font-medium block text-orange-700">Производительность (из 10)</span>
                    <span class="text-2xl font-bold text-orange-600">{{ $report['результаты']['дополнительные_наблюдения']['производительность'] }}</span>
                </div>
                @endif

                @if(isset($report['результаты']['дополнительные_наблюдения']['доступность']))
                <div class="text-center p-4 border rounded-lg bg-purple-50">
                    <span class="font-medium block text-purple-700">Доступность (из 10)</span>
                    <span class="text-2xl font-bold text-purple-600">{{ $report['результаты']['дополнительные_наблюдения']['доступность'] }}</span>
                </div>
                @endif

                @if(isset($report['результаты']['дополнительные_наблюдения']['SEO']))
                <div class="text-center p-4 border rounded-lg bg-blue-50">
                    <span class="font-medium block text-blue-700">SEO (из 10)</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $report['результаты']['дополнительные_наблюдения']['SEO'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Рекомендации -->
        @if(isset($report['результаты']['рекомендации']))
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-lg mb-3">Рекомендации по улучшению</h4>
            <ol class="list-decimal list-inside space-y-3">
                @foreach($report['результаты']['рекомендации'] as $index => $recommendation)
                <li class="text-sm p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                    {{ $recommendation }}
                </li>
                @endforeach
            </ol>
        </div>
        @endif

    @else
        <p class="text-red-500">Не удалось распарсить данные отчета</p>
    @endif
</div>