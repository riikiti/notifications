<?php

namespace App\Http\Actions\ParseDate;

use App\Models\Holidays;
use Carbon\Carbon;


class ParseDateHolidays extends ParseData
{

    public function parse()
    {
        $holidays = $this->crewlerParse('h4');
        $date = Carbon::today()->hour(8);
        foreach ($holidays as $holiday) {
            Holidays::create(['name' => $holiday, 'publish_in' => $date]);
            $date->addMinutes(30);
        }
    }
}
