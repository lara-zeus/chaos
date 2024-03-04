<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class ChaosEditRecord extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            LocaleSwitcher::make(),
            Actions\ViewAction::make()->visible(static::getResource()::hasPage('view')),
            Actions\DeleteAction::make(),
        ];
    }
}
