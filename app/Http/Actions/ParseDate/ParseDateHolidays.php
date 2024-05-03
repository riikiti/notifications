<?php

namespace App\Http\Actions\ParseDate;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateHolidays extends ParseData
{

    public function parse(): JsonResponse
    {
        $holidays = $this->crewlerParse('h4');
        return response()->json( $holidays );
    }
}
