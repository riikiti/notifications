<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateNames extends ParseData
{
    public function parse(): string
    {
        $names = $this->crewlerParse('#bloks');
        $message = "<b>Именины сегодня</b>\n" . $names[0];
        return $message;
    }

}
