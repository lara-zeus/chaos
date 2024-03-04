<?php

namespace LaraZeus\Chaos\Filament\ChaosResource\Pages;

use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class ChaosCreateRecord extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            LocaleSwitcher::make(),
        ];
    }
}
