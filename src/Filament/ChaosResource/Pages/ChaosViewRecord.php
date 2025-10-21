<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ChaosViewRecord extends ViewRecord
{
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(static::getResource()::hasPage('edit')),
        ];
    }
}
