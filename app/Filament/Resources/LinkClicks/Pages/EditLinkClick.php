<?php

namespace App\Filament\Resources\LinkClicks\Pages;

use App\Filament\Resources\LinkClicks\LinkClickResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLinkClick extends EditRecord
{
    protected static string $resource = LinkClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
