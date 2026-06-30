<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpotTypeResource\Pages;
use App\Models\SpotType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SpotTypeResource extends Resource
{
    protected static ?string $model = SpotType::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Типы площадок';
    protected static ?string $modelLabel = 'Тип площадки';
    protected static ?string $pluralModelLabel = 'Типы площадок';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('slug')
                ->label('Slug (латиница, без пробелов)')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Используется в коде, менять после создания нельзя'),
            Forms\Components\TextInput::make('icon')
                ->label('Иконка (emoji)')
                ->maxLength(5),
            Forms\Components\TextInput::make('name_ru')->label('Название (RU)')->required(),
            Forms\Components\TextInput::make('name_ro')->label('Название (RO)')->required(),
            Forms\Components\TextInput::make('name_en')->label('Название (EN)')->required(),
            Forms\Components\Select::make('category')
                ->label('Категория')
                ->options([
                    'outdoor' => 'Наружная',
                    'indoor'  => 'В помещении',
                    'digital' => 'Digital',
                    'media'   => 'Медиа (радио/блогеры)',
                ])
                ->required(),
            Forms\Components\TextInput::make('sort_order')
                ->label('Порядок сортировки')
                ->numeric()
                ->default(0),
            Forms\Components\Toggle::make('is_active')
                ->label('Активен')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')->label(''),
                Tables\Columns\TextColumn::make('name_ru')->label('RU')->searchable(),
                Tables\Columns\TextColumn::make('name_ro')->label('RO'),
                Tables\Columns\TextColumn::make('name_en')->label('EN'),
                Tables\Columns\TextColumn::make('category')->label('Категория')->badge(),
                Tables\Columns\TextColumn::make('spots_count')
                    ->label('Площадок')
                    ->getStateUsing(fn (SpotType $record) => $record->spots()->count()),
                Tables\Columns\IconColumn::make('is_active')->label('Активен')->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make()->label('Изменить'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSpotTypes::route('/'),
            'create' => Pages\CreateSpotType::route('/create'),
            'edit'   => Pages\EditSpotType::route('/{record}/edit'),
        ];
    }
}
