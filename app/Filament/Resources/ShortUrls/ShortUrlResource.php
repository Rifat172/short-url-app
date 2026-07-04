<?php

namespace App\Filament\Resources\ShortUrls;

use App\Filament\Resources\ShortUrls\Pages\CreateShortUrl;
use App\Filament\Resources\ShortUrls\Pages\EditShortUrl;
use App\Filament\Resources\ShortUrls\Pages\ListShortUrls;
use App\Filament\Resources\ShortUrls\Schemas\ShortUrlForm;
use App\Filament\Resources\ShortUrls\Tables\ShortUrlsTable;
use App\Models\ShortUrl;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ShortUrlResource extends Resource
{
    protected static ?string $model = ShortUrl::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ShortUrlForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('token')->label('Токен')->searchable(),
            TextColumn::make('long_url')->label('Длинная ссылка')->limit(50),
            TextColumn::make('user.email')
                ->label('Пользователь')
                ->searchable(['user.email']),
            TextColumn::make('hits')->label('Кликов'),
            TextColumn::make('created_at')->label('Создано'),
        ])
        ->filters([
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShortUrls::route('/'),
            'create' => CreateShortUrl::route('/create'),
            'edit' => EditShortUrl::route('/{record}/edit'),
        ];
    }
}
