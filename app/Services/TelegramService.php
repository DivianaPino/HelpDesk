<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    private $botToken;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
    }

    public function sendMessage($chatId, $message)
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

       Http::withOptions(['verify'=>false])->post($url, $params);
        
    }
}
