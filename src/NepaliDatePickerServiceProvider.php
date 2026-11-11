<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NepaliDatePickerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('nepali-date-picker')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(NepaliDateConverter::class);

        $this->app->singleton(NepaliDateManager::class, function ($app) {
            return new NepaliDateManager($app->make(NepaliDateConverter::class));
        });
    }
}
