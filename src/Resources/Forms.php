<?php

namespace JeffersonGoncalves\ConvertKit\Resources;

use JeffersonGoncalves\ConvertKit\ConvertKitClient;

class Forms
{
    public function __construct(
        protected ConvertKitClient $client,
    ) {}

    public function list(): array
    {
        return $this->client->get('/forms', useSecret: false);
    }

    /** @param array<string, mixed> $fields */
    public function subscribe(int|string $formId, string $email, ?string $firstName = null, array $fields = []): array
    {
        $body = array_filter([
            'email' => $email,
            'first_name' => $firstName,
            'fields' => $fields ?: null,
        ], fn (mixed $value) => $value !== null);

        return $this->client->post("/forms/{$formId}/subscribe", $body, useSecret: false);
    }
}
