<?php

namespace Tests\Filament\Resources\PostResource\Pages;

use LaraZeus\Chaos\Filament\ChaosResource\Pages\ChaosEditRecord;
use Tests\Filament\Resources\PostResource;

class EditPost extends ChaosEditRecord
{
    protected static string $resource = PostResource::class;
}
