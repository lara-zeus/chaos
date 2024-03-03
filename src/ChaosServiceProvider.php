<?php

namespace LaraZeus\Chaos;

use Illuminate\Database\Schema\Blueprint;
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

    public function packageBooted(): void
    {
        Blueprint::macro('actionBy', function () {
            $this->string('created_by')->nullable();
            $this->string('updated_by')->nullable();
        });

        Blueprint::macro('dropActionBy', function () {
            $this->dropColumn('created_by');
            $this->dropColumn('updated_by');
        });
    }
}
