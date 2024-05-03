<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

class SendHolidayCommand extends Command
{
    public Nutgram $bot;
    public int $chat;
    protected $signature = 'app:send-holiday';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->bot = new Nutgram(env('TELEGRAM_TOKEN'));
        $this->chat = intval(env('TELEGRAM_CHANNEL'));
    }

    public function handle()
    {
        $filePath = storage_path('app/public/image_1714568515.png');
        $caption = 'hi every one';
        try {
            $photo = fopen($filePath, 'r+');
            // $this->bot->sendMessage('Hi!', intval(env('TELEGRAM_CHANNEL')));
            $this->bot->sendPhoto(
                photo: InputFile::make($photo),
                chat_id: $this->chat,
                caption: $caption,
            );
            $this->bot->run();
            Log::channel('telegram')->info('Отправлен пост с сообщением ' . $caption);
        } catch (\Exception $err) {
            Log::channel('telegram')->info($err->getMessage());
        }
        Log::channel('telegram')->info("Command finished.");
        return Command::SUCCESS;
    }
}
