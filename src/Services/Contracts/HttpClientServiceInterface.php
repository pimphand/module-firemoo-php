<?php

namespace Firemoo\Firemoo\Services\Contracts;

interface HttpClientServiceInterface
{
    /**
     * Make HTTP request
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function request(string $method, string $url, array $options = []): array;

    /**
     * Set default headers
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders(array $headers): self;

    /**
     * Set API key and website URL for authentication
     *
     * @param string $apiKey
     * @param string $websiteUrl
     * @return self
     */
    public function setApiKey(string $apiKey, string $websiteUrl): self;

    /**
     * Set JWT token for authentication
     *
     * @param string $token
     * @return self
     */
    public function setJwtToken(string $token): self;
}
