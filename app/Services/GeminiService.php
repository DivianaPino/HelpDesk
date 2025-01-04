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
}
