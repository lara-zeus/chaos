<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ChaosListRecords extends ListRecords
{
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(static::getResource()::hasPage('create')),
        ];
    }
}
