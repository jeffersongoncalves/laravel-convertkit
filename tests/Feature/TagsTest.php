<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ConvertKit\Facades\ConvertKit;

it('lists tags using the api key', function () {
    Http::fake(['*/tags*' => Http::response(['tags' => [['id' => 1]]])]);

    $result = ConvertKit::tags()->list();

    expect($result['tags'][0]['id'])->toBe(1);
});

it('subscribes an email to a tag', function () {
    Http::fake(['*/tags/1/subscribe' => Http::response(['subscription' => ['id' => 1]])]);

    $result = ConvertKit::tags()->subscribe(1, 'jane@example.com');

    expect($result['subscription']['id'])->toBe(1);
    Http::assertSent(fn ($request) => $request['email'] === 'jane@example.com');
});

it('removes a tag from a subscriber using the api secret', function () {
    Http::fake(['*/subscribers/1/tags/2*' => Http::response([])]);

    ConvertKit::tags()->remove(2, 1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE'
        && str_contains((string) $request->url(), 'api_secret=test-api-secret'));
});
