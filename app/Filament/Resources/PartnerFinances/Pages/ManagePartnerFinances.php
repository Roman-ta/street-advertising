<?php

namespace App\Filament\Resources\PartnerFinances\Pages;

use App\Filament\Resources\PartnerFinances\PartnerFinanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePartnerFinances extends ManageRecords
{
    protected static string $resource = PartnerFinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
