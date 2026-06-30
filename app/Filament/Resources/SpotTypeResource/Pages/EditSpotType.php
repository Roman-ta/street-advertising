<?php

namespace App\Filament\Resources\SpotTypes\Pages;

use App\Filament\Resources\SpotTypes\SpotTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpotType extends EditRecord
{
    protected static string $resource = SpotTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
