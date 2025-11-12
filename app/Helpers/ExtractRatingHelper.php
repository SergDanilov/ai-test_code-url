<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ExtractRatingHelper
{
    public static function extractRatingFromResponse($response)
    {
        try {
            $decoded = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Если есть поле formatted с JSON строкой внутри
                if (isset($decoded[0]['formatted'])) {
                    $innerJson = $decoded[0]['formatted'];

                    // Извлекаем внутренний JSON из строки
                    preg_match('/\d+: (\{.*\})/', $innerJson, $matches);
                    if (isset($matches[1])) {
                        $innerDecoded = json_decode($matches[1], true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            return $innerDecoded['отчет_о_анализе']['итоговая_оценка'] ?? null;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error extracting rating: ' . $e->getMessage());
        }
        return null;
    }
}