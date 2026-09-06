<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

it('lists broadcasts using the api secret', function () {
    Http::fake(['*/broadcasts*' => Http::response(['broadcasts' => [['id' => 1]]])]);

    $result = ConvertKit::broadcasts()->list();

    expect($result['broadcasts'][0]['id'])->toBe(1);
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'api_secret=test-api-secret'));
});

it('creates a broadcast', function () {
    Http::fake(['*/broadcasts' => Http::response(['broadcast' => ['id' => 1]], 201)]);

    $result = ConvertKit::broadcasts()->create('Hello', '<p>Hi</p>', 'plain');

    expect($result['broadcast']['id'])->toBe(1);
    Http::assertSent(fn ($request) => $request['subject'] === 'Hello'
        && $request['content'] === '<p>Hi</p>'
        && $request['email_layout_template'] === 'plain');
});
