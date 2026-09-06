<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

it('lists forms using the api key', function () {
    Http::fake(['*/forms*' => Http::response(['forms' => [['id' => 1]]])]);

    $result = ConvertKit::forms()->list();

    expect($result['forms'][0]['id'])->toBe(1);
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'api_key=test-api-key')
        && ! str_contains((string) $request->url(), 'api_secret'));
});

it('subscribes an email to a form', function () {
    Http::fake(['*/forms/1/subscribe' => Http::response(['subscription' => ['id' => 1]])]);

    $result = ConvertKit::forms()->subscribe(1, 'jane@example.com', 'Jane');

    expect($result['subscription']['id'])->toBe(1);
    Http::assertSent(fn ($request) => $request['email'] === 'jane@example.com'
        && $request['first_name'] === 'Jane'
        && $request['api_key'] === 'test-api-key');
});

it('requires an api key to list forms', function () {
    config()->set('convertkit.api_key', '');

    ConvertKit::forms()->list();
})->throws(InvalidArgumentException::class);
