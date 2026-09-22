<?php

namespace Icetalker\FilamentTabRepeater;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTabRepeaterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-tab-repeater';

    public static string $viewNamespace = 'filament-tab-repeater';

    public function configurePackage(Package $package): void
    {
        
        $package->name(static::$name);

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register([
            Css::make('tab-repeater', __DIR__ . '/../resources/dist/css/tab-repeater.css'),
        ], 'filament-tab-repeater');

    }


}
