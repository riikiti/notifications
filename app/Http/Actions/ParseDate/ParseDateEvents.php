<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateEvents extends ParseData
{


    public function parse(): string
    {
        $events = $this->crewlerParse('#dv2');
        $years = $this->crewlerParse('#dv1');
        $message = "<b>Исторические события сегодня</b>\n";
        for ($i = 0; $i < count($events); $i++) {
            $message .= "- $years[$i] $events[$i]\n";
        }

        return $message;
    }


}
