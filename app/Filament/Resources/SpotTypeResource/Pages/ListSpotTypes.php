<?php

namespace App\Filament\Resources\SpotTypeResource\Pages;

use App\Filament\Resources\SpotTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpotTypes extends ListRecords
{
    protected static string $resource = SpotTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Добавить тип')];
    }
}
