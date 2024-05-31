<?php

namespace App\Filament\Widgets;

use App\Models\Holidays;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HolidaysStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Всего неопубликованных сегодня', Holidays::count())
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}
