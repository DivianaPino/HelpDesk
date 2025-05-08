<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Gemini\Laravel\Facades\Gemini;
use Carbon\Carbon;

class GeminiService
{
    private $geminiKey;

    public function __construct()
    {
        $this->geminiKey = env('GEMINI_API_KEY');
    }

    public function generateSentiment($message)
    {
        
        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Dime solamente si la siguiente oracion transmite un sentimiento positivo o negativo o neutral (si la siguiente oración contiene  un saludo informal como por ejemplo 'Hola', mostrar que el sentimiento es negativo): $message"
                        ]
                    ]
                ]
            ]
        ];
        
        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 30
            ])->post($apiUrl, $params);
            
            if ($response->successful()) {
                return $response->json();
            }

        } catch (ConnectionException $e) {
  
            throw new \Exception("Error de conexión con Gemini");
        } catch (\Exception $e) {
            throw new \Exception("Ocurrió un error inesperado");
        }
    }
    public function SpellingError($message)
    {
        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Dime solo si o no el siguiente texto tiene errores ortográficos en idioma español, pero si la palabra es 'ok' que la respuesta sea que 'no': texto: '$message'"
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 30
            ])->post($apiUrl, $params);
            
            if ($response->successful()) {
                return $response->json();
            }
            
        } catch (ConnectionException $e) {
  
            throw new \Exception("Error de conexión con Gemini");
        } catch (\Exception $e) {
            throw new \Exception("Ocurrió un error inesperado");
        }
    }

    public function CorrectErrors($message)
    {
        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Sin hacer ninguna introducción, ¿Como sería el siguiente texto sin errores ortográficos?, solo mostrar el texto corregido: $message"
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 30
            ])->post($apiUrl, $params);
            
            if ($response->successful()) {
                return $response->json();
            }
            
        } catch (ConnectionException $e) {
  
            throw new \Exception("Error de conexión con Gemini");
        } catch (\Exception $e) {
            throw new \Exception("Ocurrió un error inesperado");
        }
    }


    public function rewriteText($message) {

        $fecha_actual=Carbon::now();

        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "¿Solo dime un solo ejemplo sin ninguna introducción,  de como sería el siguiente texto que transmita un sentimiento positivo , si la siguiente oración contiene  un saludo informal como por ejemplo 'Hola', mostrar un saludo formal (con expresiones de buenos días, buenas tardes o buenas noches dependiento de la hora en Venezuela".$fecha_actual ."), teniendo en cuenta que es un mensaje a un cliente en un sistema de tickets o helpdesk?:texto: $message"
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 30
            ])->post($apiUrl, $params);
            
            if ($response->successful()) {
                return $response->json();
            }
            
        } catch (ConnectionException $e) {
  
            throw new \Exception("Error de conexión con Gemini");
        } catch (\Exception $e) {
            throw new \Exception("Ocurrió un error inesperado");
        }
    }

    public function generateSentimentClient($message)
    {


        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Dime solo si o no la siguiente oración transmite un mensaje ofensivo, frustrante, culposo e irrespetuoso (si la siguiente oración contiene un saludo informal como por ejemplo 'Hola', mostrar que Si): $message"
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 30
            ])->post($apiUrl, $params);
            
            if ($response->successful()) {
                return $response->json();
            }
            
        } catch (ConnectionException $e) {
  
            throw new \Exception("Error de conexión con Gemini");
        } catch (\Exception $e) {
            throw new \Exception("Ocurrió un error inesperado");
        }
    }

    
    public function rewriteTextClient($message) {

        $fecha_actual=Carbon::now();

        $apiUrl = config('gemini.base_url') . "/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}";
        
        $params = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Solo dime un solo ejemplo sin ninguna introducción de como sería el siguiente texto que transmita un mensaje inofensivo, sin frustración, sin culpa y respetuoso, además si la siguiente oración contiene  un saludo informal como por ejemplo 'Hola', reescribirlo con un saludo formal (con expresiones de buenos días, buenas tardes o buenas noches dependiento de la hora en Venezuela".$fecha_actual ."), teniendo en cuenta que es escrito por un cliente a un técnico de soporte?: $message"
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 30
            ])->post($apiUrl, $params);
            
            if ($response->successful()) {
                return $response->json();
            }
            
        } catch (ConnectionException $e) {
  
            throw new \Exception("Error de conexión con Gemini");
        } catch (\Exception $e) {
            throw new \Exception("Ocurrió un error inesperado");
        }
    }
}
