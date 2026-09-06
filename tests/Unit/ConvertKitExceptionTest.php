<?php

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Response as HttpResponse;
use JeffersonGoncalves\ConvertKit\Exceptions\ConvertKitException;

function fakeConvertKitResponse(int $status, array $body): Response
{
    $psr = new GuzzleHttp\Psr7\Response($status, [], json_encode($body));

    return new HttpResponse($psr);
}

it('builds the exception message from the response "message" field', function () {
    $response = fakeConvertKitResponse(404, ['message' => 'Subscriber not found']);

    $exception = ConvertKitException::fromResponse($response);

    expect($exception->getMessage())->toBe('Subscriber not found')
        ->and($exception->getCode())->toBe(404)
        ->and($exception->errorBody())->toBe(['message' => 'Subscriber not found']);
});

it('falls back to the "error_message" field when "message" is missing', function () {
    $response = fakeConvertKitResponse(422, ['error_message' => 'Email is invalid']);

    $exception = ConvertKitException::fromResponse($response);

    expect($exception->getMessage())->toBe('Email is invalid');
});

it('falls back to a generic message when the body has no known error keys', function () {
    $response = fakeConvertKitResponse(500, []);

    $exception = ConvertKitException::fromResponse($response);

    expect($exception->getMessage())->toBe('ConvertKit API error (HTTP 500).');
});
