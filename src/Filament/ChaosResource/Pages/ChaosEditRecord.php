<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class ChaosEditRecord extends EditRecord
{
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            Actions\ViewAction::make()->visible(static::getResource()::hasPage('view')),
            Actions\DeleteAction::make(),
        ];
    }
}
