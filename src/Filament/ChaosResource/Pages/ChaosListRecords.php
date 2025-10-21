<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ChaosListRecords extends ListRecords
{
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            CreateAction::make()->visible(static::getResource()::hasPage('create')),
        ];
    }
}
