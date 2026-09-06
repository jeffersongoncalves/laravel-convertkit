<?php

namespace JeffersonGoncalves\ConvertKit\Resources;

use JeffersonGoncalves\ConvertKit\ConvertKitClient;

class Broadcasts
{
    public function __construct(
        protected ConvertKitClient $client,
    ) {}

    public function list(?int $page = null): array
    {
        return $this->client->get('/broadcasts', array_filter([
            'page' => $page,
        ], fn (mixed $value) => $value !== null));
    }

    public function create(string $subject, string $content, ?string $template = null): array
    {
        $body = array_filter([
            'subject' => $subject,
            'content' => $content,
            'email_layout_template' => $template,
        ], fn (mixed $value) => $value !== null);

        return $this->client->post('/broadcasts', $body);
    }
}
