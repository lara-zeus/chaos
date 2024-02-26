<?php

namespace LaraZeus\Chaos\Filament;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChaosResource extends Resource
{
    public static function langFile(): string
    {
        return str(parent::getSlug())->explode('/')->last();
    }

    public static function getModelLabel(): string
    {
        return __(static::langFile() . '.titleSingle');
    }

    public static function getPluralModelLabel(): string
    {
        return __(static::langFile() . '.title');
    }

    // @phpstan-ignore-next-line
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (static::getModel()::isUsingSoftDelete()) {
            $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        if (static::getModel()::isUsingActionBy()) {
            $query->with(['createdBy', 'updatedBy']);
        }

        return $query;
    }
}
