<?php

namespace App\Http\Controllers;


use App\Http\Actions\GenerateImage\GenerateImageAction;
use App\Http\Actions\ParseDate\ParseDateBirthdays;
use App\Http\Actions\ParseDate\ParseDateEvents;
use App\Http\Actions\ParseDate\ParseDateHolidays;
use App\Http\Actions\ParseDate\ParseDateNames;
use App\Models\Holidays;
use Carbon\Carbon;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateController extends Controller
{

    public function index(GenerateImageAction $action,ParseDateHolidays $event)
    {
   /*     $bot = new Nutgram(env('TELEGRAM_TOKEN'));
        $photo = fopen('storage/image_1714568515.png', 'r+'); // open the file // open the file

        //$bot->sendMessage('Hi!', intval(env('TELEGRAM_CHANNEL')));
        $bot->sendPhoto(
            photo: InputFile::make($photo), // create the input file
            chat_id: intval(env('TELEGRAM_CHANNEL')),
            caption: 'hi2',
        );
        $bot->run();*/
        $event->parse();
        $records = Holidays::whereDate('publish_in', Carbon::today())->where('publish_in', '<=', now())->get();
        foreach ($records as $record) {
                $filePath = storage_path('app/public/'.$action->generateImage($record->name));
                dd($filePath);
                $photo = fopen($filePath, 'r+');
                $this->bot->sendPhoto(
                    photo: InputFile::make($photo),
                    chat_id: $this->chat,
                    caption: $record->name,
                );
                $this->bot->run();
                Log::channel('telegram')->info('Отправлен пост с сообщением ' . $record->name);
        }

        Log::channel('telegram')->info("Command finished.");
        return Command::SUCCESS;
    }


}
