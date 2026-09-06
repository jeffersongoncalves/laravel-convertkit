<?php

namespace JeffersonGoncalves\ConvertKit\Resources;

use JeffersonGoncalves\ConvertKit\ConvertKitClient;

class Sequences
{
    public function __construct(
        protected ConvertKitClient $client,
    ) {}

    public function list(): array
    {
        return $this->client->get('/sequences', useSecret: false);
    }

    /** @param array<string, mixed> $fields */
    public function subscribe(int|string $sequenceId, string $email, ?string $firstName = null, array $fields = []): array
    {
        $body = array_filter([
            'email' => $email,
            'first_name' => $firstName,
            'fields' => $fields ?: null,
        ], fn (mixed $value) => $value !== null);

        return $this->client->post("/sequences/{$sequenceId}/subscribe", $body, useSecret: false);
    }
}
