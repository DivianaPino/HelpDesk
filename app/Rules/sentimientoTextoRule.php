<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\GeminiService;

class sentimientoTextoRule implements Rule
{

    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $valueData = is_array($value) ? $value : json_decode($value, true);
        
        if (!is_array($valueData)) {
            return false;
        }
        
        $sentimientoAnalizado = $this->geminiService->generateSentiment($valueData['mensaje'] ?? '');
        dd($sentimientoAnalizado);
        
        // Comprobamos si el sentimiento analizado NO contiene "negativo"
        return $sentimientoAnalizado;
    }

    public function message()
    {
        return 'El campo :attribute debe transmitir un estado de ánimo positivo.';
    }
}
