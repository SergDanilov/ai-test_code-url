<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\UrlHelper;
use App\Helpers\ExtractRatingHelper;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Check::query();

        if ($request->has('site') && $request->site != '') {
            $query->where('site', $request->site);
        }

        $checks = $query->latest()->paginate(20); // 20 записей на страницу
        $sites = Check::select('site')->distinct()->whereNotNull('site')->pluck('site');

        return view('checks.index', compact('checks', 'sites'));
    }

    public function create()
    {
        return view('checks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'tz' => 'nullable|string',
        ]);

        try {
            // Формируем URL для API
            $apiUrl = 'https://test-oriworks.amvera.io/webhook/6f7b288e-1efe-4504-a6fd-660931327269';

            $queryParams = [
                'url' => $request->url,
            ];

            if ($request->filled('tz')) {
                $queryParams['tz'] = $request->tz;
            }

            // Извлекаем домен из URL
            $site = UrlHelper::extractDomain($request->url);

            // Выполняем запрос к API с авторизацией
            $response = Http::timeout(600)
                ->withBasicAuth('orwo', '987654321') // Добавляем авторизацию
                ->get($apiUrl, $queryParams);

            // Парсим ответ чтобы извлечь оценку
            $rating = ExtractRatingHelper::extractRatingFromResponse($response->body());

            // Сохраняем результат
            $check = Check::create([
                'name' => $request->name,
                'url' => $request->url,
                'site' => $site,
                'tz' => $request->tz,
                'response' => $response->body(),
                'status_code' => $response->status(),
                'success' => $response->successful(),
                'rating' => $rating,
            ]);

            return redirect()->route('checks.index')
                ->with('success', 'Проверка успешно выполнена и сохранена.');

        } catch (\Exception $e) {
            Log::error('API check failed: ' . $e->getMessage());

            // Извлекаем домен даже при ошибке
            $site = UrlHelper::extractDomain($request->url);

            // Сохраняем проверку с ошибкой
            Check::create([
                'name' => $request->name,
                'url' => $request->url,
                'site' => $site,
                'tz' => $request->tz,
                'response' => $e->getMessage(),
                'status_code' => 500,
                'success' => false,
                'rating' => null, // при ошибке оценка null
            ]);

            return redirect()->route('checks.index')
                ->with('error', 'Произошла ошибка при выполнении проверки: ' . $e->getMessage());
        }
    }

    public function show(Check $check)
    {
        $formattedResponse = $check->response;
        $parsedData = null;

        if ($check->response) {
            try {
                // Пробуем декодировать основной JSON
                $decoded = json_decode($check->response, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Если есть поле formatted с JSON строкой внутри
                    if (isset($decoded[0]['formatted'])) {
                        $innerJson = $decoded[0]['formatted'];

                        // Извлекаем внутренний JSON из строки
                        preg_match('/\d+: (\{.*\})/', $innerJson, $matches);
                        if (isset($matches[1])) {
                            $innerDecoded = json_decode($matches[1], true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $parsedData = $innerDecoded;
                                $formattedResponse = json_encode($innerDecoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            }
                        }
                    } else {
                        // Если нет вложенности, форматируем основной JSON
                        $formattedResponse = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                }
            } catch (\Exception $e) {
                // В случае ошибки оставляем исходный ответ
                Log::error('Error parsing JSON response: ' . $e->getMessage());
            }
        }

        return view('checks.show', compact('check', 'formattedResponse', 'parsedData'));
    }

    public function destroy(Check $check)
    {
        $check->delete();

        return redirect()->route('checks.index')
            ->with('success', 'Проверка удалена.');
    }
}