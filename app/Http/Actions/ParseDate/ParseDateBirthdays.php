<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateBirthdays extends ParseData
{

    public function parse(): JsonResponse
    {
        $birthdatys = $this->crewlerParse('#p2>p>b>big');
        $birthdatys_date = $this->crewlerParse('#p2>p>small');
        $role = $this->crewlerParse('#p2 > p:last-of-type');
        for ($i = 0; $i < count($birthdatys); $i++) {
            $mergedArray[] = [
                'name' => $birthdatys[$i],
                'date' => preg_replace('/[\(\)]/', '', $birthdatys_date[$i]),
                'role' => $role[$i]
            ];
        }
        return response()->json($mergedArray);
    }

}
