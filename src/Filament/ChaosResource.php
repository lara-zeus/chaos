<?php

namespace LaraZeus\Chaos\Filament;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChaosResource extends Resource
{
    public static ?string $langFile = '';

    /*public static function getSlug(): string
    {
        $fullSlug = str(parent::getSlug())->explode('/');

        return $fullSlug->first() . '/' . $fullSlug->last();
    }*/

    /*public static function getNavigationGroup(): ?string
    {
        return __(
            'EvoFilament.' .
            str(get_called_class())
                ->replace('App\\Filament\\', '')
                ->replace('Admin\\Resources\\', '')
                ->replace('Sites\\Resources\\', '')
                ->lower()
                ->explode('\\')
                ->first() .
            '_nav_group'
        );
    }*/

    /*public static function getModelLabel(): string
    {
        if (property_exists(new ChaosResource, 'langFile') || static::$langFile === null) {
            return __(static::$langFile . '.titleSingle');
        }

        return parent::getModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        if (! property_exists(new ChaosResource, 'langFile') || static::$langFile === null) {
            return parent::getPluralModelLabel();
        }

        return __(static::$langFile . '.title');
    }*/

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

        if (static::getModel()::filamentUsesActionBy()) {
            $query->with(['createdBy', 'updatedBy']);
        }

        return $query;
    }
}
