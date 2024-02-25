<?php

namespace LaraZeus\Chaos;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ChaosServiceProvider extends PackageServiceProvider
{
    public static string $name = 'zeus-chaos';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasTranslations()
            ->hasViews();
    }
}
