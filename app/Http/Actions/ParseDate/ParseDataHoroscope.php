<?php

namespace App\Http\Actions\ParseDate;

class ParseDataHoroscope extends ParseData
{

    public function parse(): string
    {
        $body = $this->crewlerParse('.text_box');
        $message = "<b>Гороскоп на сегодня</b>\n";
        for ($i = 0; $i < count($body); $i++) {
            $message .= "- <b>$body[$i]</b> \n ";
        }
        return $message;
    }

}
