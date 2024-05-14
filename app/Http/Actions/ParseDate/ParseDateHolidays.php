<?php

namespace App\Http\Actions\ParseDate;

use App\Models\Holidays;
use Carbon\Carbon;


class ParseDateHolidays extends ParseData
{

    public function parse()
    {
        $holidays = $this->crewlerParse('h4');
        $holidays = array_slice($holidays, 0, 10);
        $date = Carbon::today()->hour(9);
        foreach ($holidays as $holiday) {
            Holidays::create(['name' => $holiday, 'publish_in' => $date]);
            $date->addMinutes(30);
        }
    }
}
