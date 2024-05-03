<?php

namespace App\Console\Commands;

use App\Http\Actions\ParseDate\ParseDateHolidays;
use Illuminate\Console\Command;

class ParseAllHolidaysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-all-holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ParseDateHolidays $holidays)
    {
        $holidays->parse();
    }
}
