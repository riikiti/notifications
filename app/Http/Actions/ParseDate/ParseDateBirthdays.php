<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateBirthdays extends ParseData
{

    public function parse(): string
    {
        $birthdatys = $this->crewlerParse('#p2>p>b>big');
        $birthdatys_date = $this->crewlerParse('#p2>p>small');
        $role = $this->crewlerParse('#p2 > p:last-of-type');
        $message = "<b>Дни рождения сегодня</b>\n";
        for ($i = 0; $i < count($birthdatys); $i++) {
            $message .= "- <b>$birthdatys[$i]</b> $birthdatys_date[$i] \n $role[$i] \n";
        }
        return $message;
    }

}
