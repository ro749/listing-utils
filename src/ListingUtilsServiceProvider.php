<?php

namespace Ro749\ListingUtils;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ro749\ListingUtils\Commands\ListingUtilsCommand;
use Ro749\ListingUtils\Commands\MakeImageMapPro;
class ListingUtilsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('listing-utils')
            ->hasConfigFile()
            ->hasAssets()
            ->hasViews()
            ->hasMigration('create_listing_utils_table')
            ->hasCommands([
                ListingUtilsCommand::class,
                MakeImageMapPro::class,
            ])
            ->hasRoutes('web');
    }
}
