<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateNames extends ParseData
{
    public function parse(): array
    {
        $names = $this->crewlerParse('#bloks');
        return $names;
    }

}
