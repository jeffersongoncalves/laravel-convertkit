<div class="filament-hidden">

![Laravel ConvertKit](https://raw.githubusercontent.com/jeffersongoncalves/laravel-convertkit/main/art/jeffersongoncalves-laravel-convertkit.png)

</div>

# Laravel ConvertKit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-convertkit.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-convertkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-convertkit/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-convertkit/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-convertkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-convertkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-convertkit.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-convertkit)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-convertkit.svg?style=flat-square)](LICENSE.md)

A PHP/Laravel client for the [ConvertKit](https://convertkit.com/) REST API v3. Covers subscribers, forms, sequences, tags and broadcasts through a simple, typed API built on Laravel's `Http` client.

## Features

- Subscribers: list, get, update, unsubscribe
- Forms: list, subscribe
- Sequences: list, subscribe
- Tags: list, subscribe, remove
- Broadcasts: list, create
- Picks the right credential per endpoint — `api_secret` where ConvertKit requires it, `api_key` for the public/subscribe endpoints
- Throws `ConvertKitException` (with the original API error body) on any non-2xx response
- Throws `InvalidArgumentException` before hitting the API when the required credential for an endpoint isn't configured

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-convertkit
```

Publish the config file:

```bash
php artisan vendor:publish --tag=convertkit-config
```

Set your ConvertKit credentials in `.env`:

```env
CONVERTKIT_API_KEY=your-api-key
CONVERTKIT_API_SECRET=your-api-secret
```

Both values are found under **Settings > Advanced** in your ConvertKit account. `CONVERTKIT_API_KEY` is enough for the public endpoints (forms, sequences and tags: list and subscribe); every other endpoint (subscribers, broadcasts, removing a tag) needs `CONVERTKIT_API_SECRET`.

## Configuration

```php
// config/convertkit.php
return [
    'api_key' => env('CONVERTKIT_API_KEY', ''),
    'api_secret' => env('CONVERTKIT_API_SECRET', ''),
    'base_url' => env('CONVERTKIT_BASE_URL', 'https://api.convertkit.com/v3'),
];
```

## Usage

The package is resolved via the `ConvertKit` facade or by injecting `JeffersonGoncalves\ConvertKit\ConvertKit`. Each resource is exposed as a method returning a dedicated resource class.

### Subscribers

Requires `convertkit.api_secret`:

```php
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

$subscribers = ConvertKit::subscribers()->list();

// Paginated
$subscribers = ConvertKit::subscribers()->list(page: 2);

$subscriber = ConvertKit::subscribers()->get(12345);

ConvertKit::subscribers()->update(12345, firstName: 'Jane', fields: ['plan' => 'pro']);

ConvertKit::subscribers()->unsubscribe('jane@example.com');
```

### Forms

Uses `convertkit.api_key`:

```php
$forms = ConvertKit::forms()->list();

ConvertKit::forms()->subscribe(
    formId: 123,
    email: 'jane@example.com',
    firstName: 'Jane',
    fields: ['plan' => 'pro'],
);
```

### Sequences

Uses `convertkit.api_key`:

```php
$sequences = ConvertKit::sequences()->list();

ConvertKit::sequences()->subscribe(123, 'jane@example.com');
```

### Tags

`list()` and `subscribe()` use `convertkit.api_key`; `remove()` requires `convertkit.api_secret`:

```php
$tags = ConvertKit::tags()->list();

ConvertKit::tags()->subscribe(123, 'jane@example.com');

// Remove tag 123 from subscriber 12345
ConvertKit::tags()->remove(tagId: 123, subscriberId: 12345);
```

### Broadcasts

Requires `convertkit.api_secret`:

```php
$broadcasts = ConvertKit::broadcasts()->list();

// Paginated
$broadcasts = ConvertKit::broadcasts()->list(page: 2);

ConvertKit::broadcasts()->create(
    subject: 'Hello World',
    content: '<p>Hi there!</p>',
    template: 'plain',
);
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\ConvertKit\Exceptions\ConvertKitException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\ConvertKit\Exceptions\ConvertKitException;

try {
    ConvertKit::subscribers()->get(999999);
} catch (ConvertKitException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

Calling an `api_secret`-only method without `convertkit.api_secret` configured (or an `api_key`-only method without `convertkit.api_key`) throws `InvalidArgumentException` before any HTTP call is made.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
