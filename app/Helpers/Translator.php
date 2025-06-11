<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Translator
{
    public static function toSpanish($text)
    {
        try {
            $response = Http::timeout(15)->post('https://libretranslate.com/translate', [
                'q' => $text,
                'source' => 'en',
                'target' => 'es',
                'format' => 'text',
            ]);

            if ($response->successful()) {
                return $response->json()['translatedText'];
            }
        } catch (\Exception $e) {
            // Si falla, devuelve el texto original
        }

        return $text;
    }
}
