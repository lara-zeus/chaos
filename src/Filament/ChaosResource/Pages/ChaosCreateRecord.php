<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Resources\Pages\CreateRecord;

class ChaosCreateRecord extends CreateRecord
{
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
        ];
    }
}
