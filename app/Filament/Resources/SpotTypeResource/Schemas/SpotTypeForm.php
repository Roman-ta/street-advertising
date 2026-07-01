<?php

namespace App\Filament\Resources\SpotTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpotTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('name_ru')
                    ->required(),
                TextInput::make('name_ro')
                    ->required(),
                TextInput::make('name_en')
                    ->required(),
                TextInput::make('icon')
                    ->default(null),
                TextInput::make('category')
                    ->required()
                    ->default('outdoor'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
