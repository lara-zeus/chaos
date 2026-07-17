<?php

namespace Tests\Filament\Resources\PostResource\Pages;

use LaraZeus\Chaos\Filament\ChaosResource\Pages\ChaosCreateRecord;
use Tests\Filament\Resources\PostResource;

class CreatePost extends ChaosCreateRecord
{
    protected static string $resource = PostResource::class;
}
