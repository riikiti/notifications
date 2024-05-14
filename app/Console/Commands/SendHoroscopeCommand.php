<?php

namespace App\Console\Commands;

use App\Http\Actions\ParseDate\ParseDataHoroscope;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class SendHoroscopeCommand extends Command
{
    public Nutgram $bot;
    public int $chat;
    protected $signature = 'app:send-horoscope-command';


    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->bot = new Nutgram(env('TELEGRAM_TOKEN'));
        $this->chat = intval(env('TELEGRAM_CHANNEL'));
    }

    public function handle()
    {
        $dataHoroscope = new ParseDataHoroscope(
            env(
                'PARSE_DATE_HOROSCOPE',
                'https://retrofm.ru/index.php?go=goroskop'
            )
        );
        $this->bot->sendMessage(
            text: $dataHoroscope->parse(),
            chat_id: intval(env('TELEGRAM_CHANNEL')),
            parse_mode: ParseMode::HTML,
        );
        $this->bot->run();
    }
}
