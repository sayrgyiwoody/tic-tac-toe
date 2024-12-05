<?php

namespace App\Console\Commands;

use App\Models\TelegramMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FetchTelegramUpdates extends Command
{
    protected $signature = 'telegram:fetch-updates';
    protected $description = 'Fetch messages from Telegram via Long Polling';

    public function handle() {
        
    }

    private function sendReply($chatId, $message)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        Http::post($url, [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }
}
