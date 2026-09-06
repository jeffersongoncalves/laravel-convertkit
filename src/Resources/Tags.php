<?php

namespace JeffersonGoncalves\ConvertKit\Resources;

use JeffersonGoncalves\ConvertKit\ConvertKitClient;

class Tags
{
    public function __construct(
        protected ConvertKitClient $client,
    ) {}

    public function list(): array
    {
        return $this->client->get('/tags', useSecret: false);
    }

    /** @param array<string, mixed> $fields */
    public function subscribe(int|string $tagId, string $email, ?string $firstName = null, array $fields = []): array
    {
        $body = array_filter([
            'email' => $email,
            'first_name' => $firstName,
            'fields' => $fields ?: null,
        ], fn (mixed $value) => $value !== null);

        return $this->client->post("/tags/{$tagId}/subscribe", $body, useSecret: false);
    }

    public function remove(int|string $tagId, int|string $subscriberId): array
    {
        return $this->client->delete("/subscribers/{$subscriberId}/tags/{$tagId}");
    }
}
