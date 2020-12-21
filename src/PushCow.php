<?php

namespace Innoractive\PushCow;

use GuzzleHttp\Client;
use Innoractive\PushCow\Support\Str;
use Innoractive\PushCow\Support\Singleton;

class PushCow
{
    use Singleton;

    /**
     * Endpoint base URL.
     *
     * @var string
     */
    protected $base;

    /**
     * Associative array of HTTP headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * The application token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new PushCow instance.
     *
     * @param  string  $base
     * @param  string  $token
     * @return void
     */
    public function __construct($base = '', $token = '')
    {
        $this->base = $base ?: static::getConfig('base');
        $this->token = $token ?: static::getConfig('token');
        $this->headers = $this->getHeaders();
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([static::getInstance(), $method], $args);
    }

    /**
     * Get the PushCow service status.
     *
     * @return object
     */
    public function status()
    {
        $endpoint = static::getInstance()->getEndpoint('/');
        $options = ['headers' => static::getInstance()->headers];

        $response = (new Client)->request('GET', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * To register or update an existing device.
     *
     * @param  string  $deviceId
     * @param  string  $token
     * @param  int  $userId
     * @return object
     */
    public function registerDevice($deviceId, $token, $userId = null)
    {
        $endpoint = static::getInstance()->getEndpoint('devices');
        $options = [
            'headers' => static::getInstance()->headers,
            'form_params' => static::prepareData(compact('deviceId', 'token', 'userId')),
        ];

        $response = (new Client)->request('POST', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * To unregister an existing device.
     *
     * @param  string  $deviceId
     * @param  string  $token
     * @param  int  $userId
     * @return object
     */
    public function unregisterDevice($deviceId = null, $token = null, $userId = null)
    {
        $endpoint = static::getInstance()->getEndpoint('devices');
        $options = [
            'headers' => static::getInstance()->headers,
            'form_params' => static::prepareData(compact('deviceId', 'token', 'userId')),
        ];

        $response = (new Client)->request('DELETE', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Create a new message.
     *
     * @param  string  $recipients
     * @param  string  $notification
     * @param  string  $data
     * @return object
     */
    public function createMessage($recipients, $notification, $data = null)
    {
        $endpoint = static::getInstance()->getEndpoint('messages');
        $options = [
            'headers' => static::getInstance()->headers,
            'form_params' => static::prepareData(compact('recipients', 'notification', 'data')),
        ];

        $response = (new Client)->request('POST', $endpoint, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get the HTTP headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * Get the full endpoint URL.
     *
     * @param  string  $endpoint
     * @return string
     */
    protected function getEndpoint($endpoint)
    {
        return rtrim($this->base, '/').'/'.ltrim($endpoint, '/');
    }

    /**
     * Get the config for the given key.
     *
     * @param  string  $key
     * @return string
     */
    protected static function getConfig($key)
    {
        if (function_exists('config')) {
            return config('push-notification.'.strtolower($key));
        }

        if (function_exists('getenv')) {
            return getenv('PUSHCOW_'.strtoupper($key));
        }

        return '';
    }

    /**
     * Prepare the data for API request.
     *
     * @param  array  $data
     * @return array
     */
    protected static function prepareData(array $data)
    {
        $array = [];

        foreach ($data as $key => $value) {
            $array[Str::snake($key)] = $value;
        }

        return $array;
    }
}
