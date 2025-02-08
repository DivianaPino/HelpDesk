<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class GroqService
{

    private $groqKey;

    public function __construct()
    {
        $this->groqKey = env('GROQ_API_KEY'); // Asegúrate de definir esta clave en tu archivo .env
    }

    public function generateSentiment($message)
    {
        $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

        $params = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente muy util.',
                ],
                [
                    'role' => 'user',
                    'content' => "Dime solamente sin ninguna introducción si la siguiente oración transmite un estado de ánimo positivo, negativo o neutral: $message",
                ],
            ],
        ];

        $response = Http::withToken($this->groqKey)
            ->withOptions(['verify' => false])
            ->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception("Error al generar el contenido en groq: " . $response->status());
    }

    public function SpellingError($message){

        $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

        $params = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente muy util.',
                ],
                [
                    'role' => 'user',
                    'content' => "Dime solo si o no el siguiente texto tiene errores ortográficos o gramaticales, pero si la palabra es 'ok' que la respuesta sea que 'no':'$message'",
                ],
            ],
        ];

        $response = Http::withToken($this->groqKey)
            ->withOptions(['verify' => false])
            ->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception("Error al generar el contenido en groq: " . $response->status());

    }

    public function CorrectErrors($message)
    {
        $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

        $params = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente muy util.',
                ],
                [
                    'role' => 'user',
                    'content' => "¿Solo dime sin ninguna introducción de como sería el siguiente texto sin errores ortográficos o gramaticales, si no tiene solo mostrar el mismo texto: $message",
                ],
            ],
        ];

        $response = Http::withToken($this->groqKey)
            ->withOptions(['verify' => false])
            ->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception("Error al generar el contenido en groq: " . $response->status());

    }

    public function rewriteText($message) {
        $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

        $params = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente para responderle a los clientes de un sistema de mesa de ayuda',
                ],
                [
                    'role' => 'user',
                    'content' => "¿Solo muestrame sin ninguna introducción un solo texto de como sería el siguiente texto con un estado de ánimo positivo, teniendo en cuenta que dicho texto es escrito por un técnico de soporte de un sistema helpdesk?: texto: $message",
                ],
            ],
        ];

        $response = Http::withToken($this->groqKey)
            ->withOptions(['verify' => false])
            ->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception("Error al generar el contenido en groq: " . $response->status());
    }

    public function rewriteTextClient($message) {
        $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

        $params = [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente para conversar con los tecnicos de soporte de un sistema de mesa de ayuda',
                ],
                [
                    'role' => 'user',
                    'content' => "¿Solo muestrame sin ninguna introducción un solo texto de como sería el siguiente texto con un estado de ánimo positivo, , teniendo en cuenta que dicho texto es escrito por un cliente a un técnico de soporte de un sistema helpdesk?: texto: $message",
                ],
            ],
        ];

        $response = Http::withToken($this->groqKey)
            ->withOptions(['verify' => false])
            ->post($apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); 
        }

        throw new \Exception("Error al generar el contenido en groq: " . $response->status());

    }
}