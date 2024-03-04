<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ChaosListRecords extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            LocaleSwitcher::make(),
            Actions\CreateAction::make()->visible(static::getResource()::hasPage('create')),
        ];
    }
}
