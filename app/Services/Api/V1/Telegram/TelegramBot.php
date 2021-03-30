<?php


namespace App\Services\Api\V1\Telegram;


use App\Models\Request;
use Http;

class TelegramBot
{
    protected const CHAT_ID = '461587389';
    protected const API_TOKEN = '1673072370:AAHak1tXAZ3jR5rLN9W0vHuArlKYaT-DZRQ';

    /**
     * @param Request $request
     */
    public static function notifyAdmin(Request $request)
    {
        $text = "<b>Новая заявка</b> \nБлогер с ником <a href='https://www.tiktok.com/{$request->name}'>{$request->name}</a> подал заявку на создание аккаунта.";
        self::sendMessage($text);

    }

    protected static function sendMessage($text)
    {
        $text = urlencode($text);
        Http::get('https://api.telegram.org/bot' . self::API_TOKEN . '/sendMessage?chat_id=' . self::CHAT_ID . '&text=' . $text . '&parse_mode=html');
    }
}
