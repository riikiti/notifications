<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateEvents extends ParseData
{


    public function parse(): JsonResponse
    {
        $this->create();
        $events = $this->crewlerParse('#dv2');
        $years = $this->crewlerParse('#dv1');
        $mergedArray = [];

        for ($i = 0; $i < count($events); $i++) {
            $mergedArray[] = ['event' => $events[$i], 'date' => $years[$i]];
        }
        return response()->json($mergedArray);
    }


}
