<?php

namespace App\Http\Controllers\IA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use App\Services\GeminiService;

class SentimentController extends Controller
{

    public function formSentiment(){
         return view('myViews.Admin.sentiment');
    }

    public function index(GeminiService $geminiService, Request $request)
    {  
        

        try {
            // Llama al servicio y obtiene el resultado
            $result = $geminiService->generateSentiment($request->mensaje);
    
            // Aquí asumimos que $result ya es un array asociativo
            // Capturamos solo el texto analizado
            $textAnalizado = $result['candidates'][0]['content']['parts'][0]['text'];
    
            // Devuelve el resultado como JSON
            return response()->json(['text' => $textAnalizado]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

            
      
    }
}
