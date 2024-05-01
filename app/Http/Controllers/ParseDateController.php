<?php

namespace App\Http\Controllers;


use App\Http\Actions\GenerateImage\GenerateImageAction;
use App\Http\Actions\ParseDate\ParseDateEvents;
use App\Http\Actions\ParseDate\ParseDateHolidays;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateController extends Controller
{

    public function index(GenerateImageAction $action,ParseDateHolidays $event)
    {
        return $event->parse();
        //return $action->generateImage('Всемирный день котов');
    }


}
