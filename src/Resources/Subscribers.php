<?php

namespace JeffersonGoncalves\ConvertKit\Resources;

use JeffersonGoncalves\ConvertKit\ConvertKitClient;

class Subscribers
{
    public function __construct(
        protected ConvertKitClient $client,
    ) {}

    public function list(?int $page = null): array
    {
        return $this->client->get('/subscribers', array_filter([
            'page' => $page,
        ], fn (mixed $value) => $value !== null));
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/subscribers/{$id}");
    }

    /** @param array<string, mixed> $fields */
    public function update(int|string $id, ?string $firstName = null, array $fields = []): array
    {
        $body = array_filter([
            'first_name' => $firstName,
            'fields' => $fields ?: null,
        ], fn (mixed $value) => $value !== null);

        return $this->client->put("/subscribers/{$id}", $body);
    }

    public function unsubscribe(string $email): array
    {
        return $this->client->put('/unsubscribe', ['email' => $email]);
    }
}
