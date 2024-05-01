<?php

namespace App\Http\Actions\Bots;

use App\Models\User;

interface Bot
{
    public function post(array $groups, string $message, array $params = []);

    public function sendImage(int $userTelegramId, string $filepath, string $caption);
}
