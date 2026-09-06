<?php

namespace JeffersonGoncalves\ConvertKit\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class ConvertKitException extends RuntimeException
{
    /** @var array<string, mixed> */
    protected array $errorBody = [];

    public static function fromResponse(Response $response): self
    {
        $body = (array) ($response->json() ?? []);

        $message = $body['message']
            ?? $body['error_message']
            ?? "ConvertKit API error (HTTP {$response->status()}).";

        $exception = new self($message, $response->status());
        $exception->errorBody = $body;

        return $exception;
    }

    /** @return array<string, mixed> */
    public function errorBody(): array
    {
        return $this->errorBody;
    }
}
