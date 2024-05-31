<?php

namespace App\Filament\Resources\HolidaysResource\Pages;

use App\Filament\Resources\HolidaysResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHolidays extends EditRecord
{
    protected static string $resource = HolidaysResource::class;
    protected static ?string $title = 'Праздник';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
