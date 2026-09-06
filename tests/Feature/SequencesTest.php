<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

it('lists sequences using the api key', function () {
    Http::fake(['*/sequences*' => Http::response(['courses' => [['id' => 1]]])]);

    $result = ConvertKit::sequences()->list();

    expect($result['courses'][0]['id'])->toBe(1);
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'api_key=test-api-key'));
});

it('subscribes an email to a sequence', function () {
    Http::fake(['*/sequences/1/subscribe' => Http::response(['subscription' => ['id' => 1]])]);

    $result = ConvertKit::sequences()->subscribe(1, 'jane@example.com');

    expect($result['subscription']['id'])->toBe(1);
    Http::assertSent(fn ($request) => $request['email'] === 'jane@example.com');
});
