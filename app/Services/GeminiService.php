<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    private $geminiKey;

    public function __construct()
    {
        $this->geminiKey = env('GEMINI_API_KEY');
    }

    public function generateSentiment($message)
    {


        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Dime solamente si la siguiente oración transmite un sentimiento positivo o negativo o neutral: $message"
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withOptions(['verify'=>false])->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); // Retorna el JSON de la respuesta
        }

        throw new \Exception("Error al generar el contenido: " . $response->status());
    }

    public function SpellingError($message)
    {
        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Dime solo si o no el siguiente texto tiene errores ortográficos o gramaticales, pero si la palabra es 'ok' que la respuesta sea que 'no': texto: '$message'"
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withOptions(['verify'=>false])->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); // Retorna el JSON de la respuesta
        }

        throw new \Exception("Error al generar el contenido: " . $response->status());
    }

    public function CorrectErrors($message)
    {
        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "¿Como sería el siguiente texto sin errores ortográficos o gramaticales, si no tiene solo mostrar el mismo texto: $message"
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withOptions(['verify'=>false])->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); // Retorna el JSON de la respuesta
        }

        throw new \Exception("Error al generar el contenido: " . $response->status());
    }


    public function rewriteText($message) {

        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "¿Solo dime un solo ejemplo de como sería el siguiente texto con un estado de ánimo positivo, teniendo en cuenta que dicho texto es escrito por un tecnico de soporte de un sistema de mesa de ayuda?: $message"
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withOptions(['verify'=>false])->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); // Retorna el JSON de la respuesta
        }

        throw new \Exception("Error al generar el contenido: " . $response->status());
    }
}
