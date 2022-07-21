<?php

namespace Mikenewbuild\Cloudflare;

use Mikenewbuild\Cloudflare\Response;

class ApiClient
{
    protected string $api_key;

    protected string $api_base;

    public function __construct()
    {
        $this->api_key = config('api_key');
        $this->api_base = config('api_base');
    }

    public function verify(): Response
    {
        $request = $this->request($this->url('user/tokens/verify'), $this->options());

        return $this->response($request);
    }

    public function zones(): Response
    {
        return $this->get('zones');
    }

    public function findZone(string $domain): Response
    {
        foreach ($this->zones()->result as $result) {
            if ($result->name === $domain) {
                return new Response($result);
            }
        }

        throw new \OutOfBoundsException("The domain '{$domain}' could not be found in this zone.");
    }

    public function get(string $endpoint): Response
    {
        $request = $this->request($this->url($endpoint), $this->options());

        return $this->response($request);
    }

    public function delete(string $endpoint): Response
    {
        $request = $this->request($this->url($endpoint), $this->options('DELETE'));

        return $this->response($request);
    }

    public function request($url, $options): string
    {
        $handle = curl_init($url);

        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $options['method']);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $options['headers']);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true); // follow redirects
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); // return string

        return curl_exec($handle);
    }

    public function options($method = 'GET', $body = ''): array
    {
        return [
            'method' => $method,
            'headers' => [
                'Authorization: Bearer ' . $this->api_key,
                'Content-Type: application/json',
            ],
            'body' => $body
        ];
    }

    public function base(): string
    {
        return $this->api_base;
    }

    public function url($endpoint = ''): string
    {
        return $this->base() . $endpoint;
    }

    public function response(string $request): Response
    {
        return new Response($request);
    }
}
