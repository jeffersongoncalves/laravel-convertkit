<?php

use JeffersonGoncalves\ConvertKit\ConvertKit as ConvertKitManager;
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

it('merges the default config', function () {
    expect(config('convertkit.base_url'))->toBe('https://api.convertkit.com/v3');
});

it('resolves the facade to the manager singleton', function () {
    expect(ConvertKit::getFacadeRoot())->toBeInstanceOf(ConvertKitManager::class);
});

it('always resolves the same manager instance', function () {
    expect(app(ConvertKitManager::class))->toBe(app(ConvertKitManager::class));
});
