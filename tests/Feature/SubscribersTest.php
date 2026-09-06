<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

it('lists subscribers using the api secret', function () {
    Http::fake([
        '*/subscribers*' => Http::response(['subscribers' => [['id' => 1]]]),
    ]);

    $result = ConvertKit::subscribers()->list();

    expect($result['subscribers'][0]['id'])->toBe(1);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'api_secret=test-api-secret'));
});

it('lists subscribers for a given page', function () {
    Http::fake(['*/subscribers*' => Http::response(['subscribers' => []])]);

    ConvertKit::subscribers()->list(page: 2);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'page=2'));
});

it('gets a single subscriber', function () {
    Http::fake(['*/subscribers/1*' => Http::response(['subscriber' => ['id' => 1]])]);

    $result = ConvertKit::subscribers()->get(1);

    expect($result['subscriber']['id'])->toBe(1);
});

it('updates a subscriber', function () {
    Http::fake(['*/subscribers/1*' => Http::response(['subscriber' => ['id' => 1, 'first_name' => 'Jane']])]);

    $result = ConvertKit::subscribers()->update(1, 'Jane', ['plan' => 'pro']);

    expect($result['subscriber']['first_name'])->toBe('Jane');
    Http::assertSent(fn ($request) => $request['first_name'] === 'Jane' && $request['fields']['plan'] === 'pro');
});

it('unsubscribes an email', function () {
    Http::fake(['*/unsubscribe*' => Http::response(['subscriber' => ['id' => 1]])]);

    ConvertKit::subscribers()->unsubscribe('jane@example.com');

    Http::assertSent(fn ($request) => $request['email'] === 'jane@example.com');
});

it('requires an api secret to list subscribers', function () {
    config()->set('convertkit.api_secret', '');

    ConvertKit::subscribers()->list();
})->throws(InvalidArgumentException::class);
