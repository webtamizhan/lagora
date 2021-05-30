<?php

namespace Webtamizhan\Lagora;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webtamizhan\Lagora\Commands\LagoraCommand;

class LagoraServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lagora')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_lagora_table')
            ->hasCommand(LagoraCommand::class);
    }
}
