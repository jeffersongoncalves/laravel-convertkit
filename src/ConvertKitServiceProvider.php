<?php

namespace JeffersonGoncalves\ConvertKit;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConvertKitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('convertkit')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ConvertKit::class, function () {
            $apiKey = config('convertkit.api_key');
            $apiSecret = config('convertkit.api_secret');

            return new ConvertKit(
                $apiKey !== null && $apiKey !== '' ? (string) $apiKey : null,
                $apiSecret !== null && $apiSecret !== '' ? (string) $apiSecret : null,
                (string) config('convertkit.base_url', 'https://api.convertkit.com/v3'),
            );
        });
    }
}
