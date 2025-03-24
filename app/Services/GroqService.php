<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class GroqService
{

    private $groqKey;

    public function __construct()
    {
        $this->groqKey = env('GROQ_API_KEY'); 
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
                    'content' => "Dime solamente sin ninguna introducción si la siguiente oracion transmite un sentimiento positivo o negativo o neutral (si la siguiente oración contiene  un saludo informal como por ejemplo 'Hola', mostrar que el sentimiento es negativo): $message",
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
                    'content' => "Dime solo si o no el siguiente texto tiene errores ortográficos en idioma español, pero si la palabra es 'ok' que la respuesta sea que 'no': texto:'$message'",
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
                    'content' => "Sin hacer ninguna introducción, ¿Como sería el siguiente texto sin errores ortográficos?, solo mostrar el texto corregido: $message",
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

        $fecha_actual=Carbon::now();

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
                    'content' => "Solo dime un solo ejemplo sin ninguna introducción,  de como sería el siguiente texto que transmita un sentimiento positivo , si la siguiente oración contiene  un saludo informal como por ejemplo 'Hola', mostrar un saludo formal (con expresiones de buenos días, buenas tardes o buenas noches dependiento de la hora en Venezuela".$fecha_actual ."), teniendo en cuenta que es un mensaje a un cliente en un sistema de tickets o helpdesk:text: texto: $message",
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

    public function generateSentimentClient($message)
    {
        $fecha_actual=Carbon::now();

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
                    'content' => "Dime solo si o no la siguiente oración transmite un mensaje ofensivo, frustrante, culposo e irrespetuoso (si la siguiente oración contiene un saludo informal como por ejemplo 'Hola', mostrar que Si): $message",
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
                    'content' => "Solo dime un solo ejemplo sin ninguna introducción de como sería el siguiente texto que transmita un mensaje inofensivo, sin frustración, sin culpa y respetuoso, además si la siguiente oración contiene  un saludo informal como por ejemplo 'Hola', reescribirlo con un saludo formal (con expresiones de buenos días, buenas tardes o buenas noches dependiento de la hora en Venezuela".$fecha_actual ."), teniendo en cuenta que es escrito por un cliente a un técnico de soporte?: $message",
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