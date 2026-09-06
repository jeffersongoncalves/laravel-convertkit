<?php

namespace JeffersonGoncalves\ConvertKit\Tests;

use JeffersonGoncalves\ConvertKit\ConvertKitServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ConvertKitServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('convertkit.api_key', 'test-api-key');
        $app['config']->set('convertkit.api_secret', 'test-api-secret');
    }
}
