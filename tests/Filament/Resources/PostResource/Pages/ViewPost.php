<?php

namespace Tests\Filament\Resources\PostResource\Pages;

use LaraZeus\Chaos\Filament\ChaosResource\Pages\ChaosViewRecord;
use Tests\Filament\Resources\PostResource;

class ViewPost extends ChaosViewRecord
{
    protected static string $resource = PostResource::class;
}
