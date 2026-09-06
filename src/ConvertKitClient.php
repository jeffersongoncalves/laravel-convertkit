<?php

namespace JeffersonGoncalves\ConvertKit;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use JeffersonGoncalves\ConvertKit\Exceptions\ConvertKitException;

/**
 * Thin wrapper around Laravel's Http client for the ConvertKit REST API v3.
 *
 * ConvertKit authenticates via an `api_secret` or `api_key` query/body
 * parameter (not a header) — most endpoints require `api_secret`, a handful
 * of public/subscribe endpoints accept the more limited `api_key` instead.
 *
 * ponytail: Http::retry() already covers transient-failure retries if ever
 * needed later — no custom retry/backoff layer built here speculatively.
 */
class ConvertKitClient
{
    public function __construct(
        protected ?string $apiKey,
        protected ?string $apiSecret,
        protected string $baseUrl,
    ) {}

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = [], bool $useSecret = true): array
    {
        return $this->send('get', $path, array_merge($query, $this->authParam($useSecret)));
    }

    /** @param array<string, mixed>|null $body */
    public function post(string $path, ?array $body = null, bool $useSecret = true): array
    {
        return $this->send('post', $path, array_merge($body ?? [], $this->authParam($useSecret)));
    }

    /** @param array<string, mixed>|null $body */
    public function put(string $path, ?array $body = null, bool $useSecret = true): array
    {
        return $this->send('put', $path, array_merge($body ?? [], $this->authParam($useSecret)));
    }

    public function delete(string $path, bool $useSecret = true): array
    {
        return $this->send('delete', $path, $this->authParam($useSecret));
    }

    /** @param array<string, mixed> $data */
    protected function send(string $method, string $path, array $data): array
    {
        $http = Http::baseUrl($this->baseUrl)->acceptJson();

        // ConvertKit expects auth on the query string for GET/DELETE (not a
        // JSON body), so the query is appended manually for those verbs.
        if (in_array($method, ['get', 'delete'], true)) {
            $separator = str_contains($path, '?') ? '&' : '?';
            $response = $http->{$method}($path.$separator.http_build_query($data));
        } else {
            $response = $http->asJson()->{$method}($path, $data);
        }

        if ($response->failed()) {
            throw ConvertKitException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }

    /** @return array<string, string> */
    protected function authParam(bool $useSecret): array
    {
        if ($useSecret) {
            if (empty($this->apiSecret)) {
                throw new InvalidArgumentException('A ConvertKit API secret is required for this endpoint. Set "convertkit.api_secret".');
            }

            return ['api_secret' => $this->apiSecret];
        }

        if (empty($this->apiKey)) {
            throw new InvalidArgumentException('A ConvertKit API key is required for this endpoint. Set "convertkit.api_key".');
        }

        return ['api_key' => $this->apiKey];
    }
}
