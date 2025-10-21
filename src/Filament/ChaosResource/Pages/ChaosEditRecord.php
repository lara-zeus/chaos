<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class ChaosEditRecord extends EditRecord
{
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            ViewAction::make()->visible(static::getResource()::hasPage('view')),
            DeleteAction::make(),
        ];
    }
}
