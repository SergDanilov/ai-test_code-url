<?php

namespace App\Helpers;

class UrlHelper
{
    public static function extractDomain($url)
    {
        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['host'])) {
            return null;
        }

        $host = $parsedUrl['host'];

        // Убираем www. если есть
        if (strpos($host, 'www.') === 0) {
            $host = substr($host, 4);
        }

        return $host;
    }
}