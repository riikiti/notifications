<?php

namespace App\Http\Actions\ParseDate;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateEvents implements ParseDate
{
    public Crawler $crawler;

    public function __construct()
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ])->get(
            env(
                'PARSE_DATE_EVENTS',
                'https://www.calend.ru/events/' . Carbon::now()->format('Y-n-j') . '/'
            ) . Carbon::now()->format('Y-n-j') . '/'
        );
        $this->crawler = new Crawler($response->body());
    }

    public function parse(): JsonResponse
    {
        $events = $this->crewlerParse('div.caption > span.title > a');
        $years = $this->crewlerParse('div.caption > span.year');
        $mergedArray = [];

        for ($i = 0; $i < count($events); $i++) {
            $mergedArray[] = ['event' => $events[$i], 'date' => $years[$i]];
        }
        return response()->json($mergedArray);
    }

    public function crewlerParse(string $query)
    {
        return $this->crawler->filter($query)->each(function (Crawler $node) {
            return $node->text();
        });
    }
}
