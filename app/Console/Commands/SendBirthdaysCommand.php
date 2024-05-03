<?php

namespace App\Console\Commands;

use App\Http\Actions\ParseDate\ParseDateBirthdays;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class SendBirthdaysCommand extends Command
{
    public Nutgram $bot;
    public int $chat;
    protected $signature = 'app:send-birthdays-command';

    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
        $this->bot = new Nutgram(env('TELEGRAM_TOKEN'));
        $this->chat = intval(env('TELEGRAM_CHANNEL'));
    }

    public function handle(ParseDateBirthdays $birthdays)
    {
        $this->bot->sendMessage(
            text: $birthdays->parse(),
            chat_id: intval(env('TELEGRAM_CHANNEL')),
            parse_mode: ParseMode::HTML,
        );
        $this->bot->run();
    }
}
