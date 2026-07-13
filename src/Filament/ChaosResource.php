<?php

namespace LaraZeus\Chaos\Filament;

use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChaosResource extends Resource
{
    protected static string | BackedEnum | null $navigationIcon = 'tabler-point-filled';

    public static function langFile(): string
    {
        return str(parent::getSlug())->explode('/')->last();
    }

    public static function getModelLabel(): string
    {
        if (! config('zeus-chaos.use_locale', true)) {
            return parent::getModelLabel();
        }

        return __(static::langFile() . '.titleSingle');
    }

    public static function getPluralModelLabel(): string
    {
        if (! config('zeus-chaos.use_locale', true)) {
            return parent::getPluralModelLabel();
        }

        return __(static::langFile() . '.title');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // @phpstan-ignore-next-line
        if (static::getModel()::isUsingSoftDelete()) {
            $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        // @phpstan-ignore-next-line
        if (static::getModel()::isUsingActionBy()) {
            $query->with(['createdBy', 'updatedBy']);
        }

        return $query;
    }
}
