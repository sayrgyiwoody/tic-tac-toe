<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Symfony\Component\HttpFoundation\IpUtils;

class TelegramController extends Controller
{
    //Show message form
    public function messageForm(Request $request)
    {
        return Inertia::render('Telegram/MessageForm');
    }

    //Send message to telegram bot
    public function sendMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        $chatId = '1692111321';
        $text = "Name: " . $request->name . "\n";
        $text .= "Email: " . $request->email . "\n";
        $text .= "Subject: " . $request->subject . "\n";
        $text .= "Message: " . $request->message;

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);

        return to_route('telegram.message-form');
    }

    //Get updates from telegram bot
    public function getUpdates(Request $request)
    {
        $updates = Telegram::getUpdates();
        return $updates;
    }

}
