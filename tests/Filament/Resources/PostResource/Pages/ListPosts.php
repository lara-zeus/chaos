<?php

namespace Tests\Filament\Resources\PostResource\Pages;

use LaraZeus\Chaos\Filament\ChaosResource\Pages\ChaosListRecords;
use Tests\Filament\Resources\PostResource;

class ListPosts extends ChaosListRecords
{
    protected static string $resource = PostResource::class;
}
