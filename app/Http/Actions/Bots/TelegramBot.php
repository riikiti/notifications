<?php

namespace App\Http\Actions\Bots;


use BotMan\BotMan\BotMan;

class TelegramBot implements Bot
{
    public function post(array $groups, string $message, array $params = [])
    {
        // TODO: Implement postToGroups() method.
    }

    public function sendImage(int $userTelegramId, string $filepath, string $caption)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot$token/sendPhoto?";
        $botman = app(BotMan::class);
        $botman->telegram->sendPhoto($userTelegramId, $filepath, $caption, ['parse_mode' => 'HTML']);

        return $botman->telegram->getResponse();;
    }
}
