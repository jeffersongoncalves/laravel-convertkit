<?php

namespace JeffersonGoncalves\ConvertKit;

use JeffersonGoncalves\ConvertKit\Resources\Broadcasts;
use JeffersonGoncalves\ConvertKit\Resources\Forms;
use JeffersonGoncalves\ConvertKit\Resources\Sequences;
use JeffersonGoncalves\ConvertKit\Resources\Subscribers;
use JeffersonGoncalves\ConvertKit\Resources\Tags;

/**
 * Entry point exposing one resource per ConvertKit API v3 group.
 */
class ConvertKit
{
    protected ConvertKitClient $client;

    public function __construct(?string $apiKey, ?string $apiSecret, string $baseUrl)
    {
        $this->client = new ConvertKitClient($apiKey, $apiSecret, $baseUrl);
    }

    public function subscribers(): Subscribers
    {
        return new Subscribers($this->client);
    }

    public function forms(): Forms
    {
        return new Forms($this->client);
    }

    public function sequences(): Sequences
    {
        return new Sequences($this->client);
    }

    public function tags(): Tags
    {
        return new Tags($this->client);
    }

    public function broadcasts(): Broadcasts
    {
        return new Broadcasts($this->client);
    }
}
