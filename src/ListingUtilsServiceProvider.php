<?php

namespace Ro749\ListingUtils;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ro749\ListingUtils\Commands\ListingUtilsCommand;
use Ro749\ListingUtils\Commands\MakeImageMapPro;
use Ro749\ListingUtils\Commands\MakePlans;
use Ro749\ListingUtils\Commands\ReadUnits;
use Illuminate\Support\Facades\Blade;
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
                MakePlans::class,
                ReadUnits::class
            ])
            ->hasRoutes('web');
    }

    public function packageBooted(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'listingutils');
        Blade::component('listingutils::Plans.Lines.plan-line', 'plan-line');
        Blade::component('listingutils::Plans.Lines.fillable-line', 'fillable-line');
        Blade::component('listingutils::Plans.Lines.months-lines', 'months-lines');
        Blade::component('listingutils::Plans.Lines.discount-line', 'discount-line');
        Blade::component('listingutils::Plans.Personalized.plan-line', 'personalized-line');
        Blade::component('listingutils::Plans.Personalized.fillable-line', 'personalized-fillable-line');
        Blade::component('listingutils::Plans.Personalized.months-lines', 'personalized-months-lines');
        Blade::component('listingutils::Plans.Personalized.discount-line', 'personalized-discount-line');
    }

    public function register()
    {
        parent::register();
        $packageConfig = require __DIR__.'/../config/listing-utils.php';
        config(['overrides' => $this->mergeConfigs($packageConfig['overrides'], config('overrides', []))]);    
    }

    protected function mergeConfigs(array $package, array $project): array
    {
        foreach ($project as $key => $value) {
            $package[$key] = (is_array($value) && isset($package[$key]) && is_array($package[$key]))
                ? $this->mergeConfigs($package[$key], $value)
                : $value;
        }
        return $package;
    }
}
