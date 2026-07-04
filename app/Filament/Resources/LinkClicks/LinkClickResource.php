<?php

namespace App\Filament\Resources\LinkClicks;

use App\Filament\Resources\LinkClicks\Pages\CreateLinkClick;
use App\Filament\Resources\LinkClicks\Pages\EditLinkClick;
use App\Filament\Resources\LinkClicks\Pages\ListLinkClicks;
use App\Filament\Resources\LinkClicks\Schemas\LinkClickForm;
use App\Filament\Resources\LinkClicks\Tables\LinkClicksTable;
use App\Models\LinkClick;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\DateFilter;

class LinkClickResource extends Resource
{
    protected static ?string $model = LinkClick::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LinkClickForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shortUrl.token')
                    ->label('Ссылка')
                    ->formatStateUsing(fn ($record) => '/r/' . ($record->shortUrl?->token ?? ''))
                    ->searchable()
                    ->url(fn ($record) => '/r/' . ($record->shortUrl?->token ?? ''))
                    ->openUrlInNewTab(),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->sortable()
                    ->limit(45),

                TextColumn::make('clicked_at')
                    ->label('Время перехода')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('user_agent')
                    ->label('User-Agent')
                    ->limit(60)
                    ->tooltip('Полный User-Agent запроса'),
            ])
            ->filters([
                Filter::make('shortUrl.token')
                    ->label('Токен ссылки')
                    ->query(function (Builder $query, string $search): Builder {
                        return $query->whereHas('shortUrl', fn ($q) => $q->where('token', 'like', "%{$search}%"));
                    }),
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
            'index' => ListLinkClicks::route('/'),
        ];
    }
}
