<?php

namespace Innoractive\PushCow;

use GuzzleHttp\Client;
use Innoractive\PushCow\Support\Str;

class PushCow
{
    /**
     * The HTTP headers.
     */
    protected array $headers;

    /**
     * Create a new PushCow instance.
     *
     * @return void
     */
    public function __construct(
        protected string $base,
        protected string $token,
    ) {
        $this->headers = [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * Get the PushCow service status.
     */
    public function status(): object
    {
        $endpoint = $this->getEndpoint('/');
        $options = ['headers' => $this->headers];

        $response = (new Client)->request('GET', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * To register or update an existing device.
     */
    public function registerDevice(
        string $platform,
        string $deviceId,
        string $token,
        ?string $userId = null,
    ): object {
        $endpoint = $this->getEndpoint('devices');
        $options = [
            'headers' => $this->headers,
            'form_params' => static::prepareData(
                compact('platform', 'deviceId', 'token', 'userId')
            ),
        ];

        $response = (new Client)->request('POST', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * To unregister an existing device.
     */
    public function unregisterDevice(
        ?string $deviceId = null,
        ?string $token = null,
        ?string $userId = null,
    ): object {
        $endpoint = $this->getEndpoint('devices');
        $options = [
            'headers' => $this->headers,
            'form_params' => static::prepareData(
                compact('deviceId', 'token', 'userId')
            ),
        ];

        $response = (new Client)->request('DELETE', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Create a new message.
     */
    public function createMessage(
        string $recipients,
        string $notification,
        ?string $data = null,
        ?string $options = null,
    ): object {
        $endpoint = $this->getEndpoint('messages');
        $options = [
            'headers' => $this->headers,
            'form_params' => static::prepareData(
                compact('recipients', 'notification', 'data', 'options')
            ),
        ];

        $response = (new Client)->request('POST', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get the full API endpoint URL.
     */
    protected function getEndpoint(string $endpoint): string
    {
        return rtrim($this->base, '/').'/'.ltrim($endpoint, '/');
    }

    /**
     * Prepare data for the API request.
     */
    protected static function prepareData(array $data): array
    {
        return array_combine(
            array_map(fn ($key) => Str::snake($key), array_keys($data)),
            array_values($data),
        );
    }
}
