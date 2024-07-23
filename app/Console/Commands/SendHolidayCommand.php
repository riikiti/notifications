<?php

namespace App\Console\Commands;

use App\Http\Actions\GenerateImage\GenerateImageAction;
use App\Http\Actions\ParseDate\ParseDateHolidays;
use App\Models\Holidays;
use Carbon\Carbon;
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

    public function handle(GenerateImageAction $action)
    {
        $records = Holidays::whereDate('publish_in', Carbon::today())->where('publish_in', '<=', now())->get();
        foreach ($records as $record) {
            $filePath = storage_path('app/public/' . $action->generateImage($record->name));
            $photo = fopen($filePath, 'r+');
            $this->bot->sendPhoto(
                photo: InputFile::make($photo),
                chat_id: $this->chat,
                caption: $record->name,
            );
            $record->delete();
            Log::channel('telegram')->info('Отправлен пост с сообщением ' . $record->name);
        }

        return Command::SUCCESS;
    }
}
