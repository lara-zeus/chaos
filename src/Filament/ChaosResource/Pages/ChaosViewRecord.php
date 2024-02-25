<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ChaosViewRecord extends ViewRecord
{
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->visible(static::getResource()::hasPage('edit')),
        ];
    }
}
