<?php

namespace App\Console\Commands;

use App\Http\Actions\ParseDate\ParseDateNames;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

class SendNamesCommand extends Command
{
    public Nutgram $bot;
    public int $chat;
    protected $signature = 'app:send-names-command';


    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->bot = new Nutgram(env('TELEGRAM_TOKEN'));
        $this->chat = intval(env('TELEGRAM_CHANNEL'));
    }

    public function handle(ParseDateNames $names)
    {
        $this->bot->sendMessage($names->parse()[0], intval(env('TELEGRAM_CHANNEL')));
        $this->bot->run();
    }
}
