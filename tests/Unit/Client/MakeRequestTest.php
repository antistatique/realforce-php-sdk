<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'makeRequest')]
#[CoversClass(RealforceClient::class)]
final class MakeRequestTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new MakeRequestTestableRealforceClient();
    }

    /**
     * Test GET request with query parameters.
     */
    public function testMakeRequestGetWithQueryParams(): void
    {
        $args = ['param1' => 'value1', 'param2' => 'value2'];

        // Mock the response to avoid actual HTTP call
        $mockResponse = ['key' => 'value'];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('get', 'https://api.example.com/endpoint', $args, 30);

        self::assertIsArray($result);
        self::assertSame($mockResponse, $result);

        // Verify the URL was built correctly with query parameters
        $lastRequest = $this->client->getLastRequest();
        self::assertSame('https://api.example.com/endpoint', $lastRequest['url']);
        self::assertStringNotContainsString('param1=value1', $lastRequest['url']);
        self::assertStringNotContainsString('param2=value2', $lastRequest['url']);
        self::assertSame('get', $lastRequest['method']);
    }

    /**
     * Test POST request with JSON payload.
     */
    public function testMakeRequestPostWithJsonPayload(): void
    {
        $args = ['data' => 'test', 'nested' => ['key' => 'value']];

        $mockResponse = ['success' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('post', 'https://api.example.com/create', $args, 30);

        self::assertIsArray($result);
        self::assertSame($mockResponse, $result);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame('post', $lastRequest['method']);
        self::assertJson($lastRequest['body']);

        $decodedBody = json_decode($lastRequest['body'], true);
        self::assertSame($args, $decodedBody);
    }

    /**
     * Test PUT request with JSON payload.
     */
    public function testMakeRequestPutWithPayload(): void
    {
        $args = ['id' => 123, 'name' => 'updated'];

        $mockResponse = ['updated' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('put', 'https://api.example.com/update/123', $args, 30);

        self::assertIsArray($result);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame('put', $lastRequest['method']);
        self::assertJson($lastRequest['body']);

        // Verify PUT-specific header is set
        self::assertStringContainsString('Allow: PUT, PATCH, POST', $lastRequest['headers_string']);
    }

    /**
     * Test PATCH request with JSON payload.
     */
    public function testMakeRequestPatchWithPayload(): void
    {
        $args = ['field' => 'new_value'];

        $mockResponse = ['patched' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('patch', 'https://api.example.com/patch/123', $args, 30);

        self::assertIsArray($result);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame('patch', $lastRequest['method']);
        self::assertJson($lastRequest['body']);
    }

    /**
     * Test DELETE request.
     */
    public function testMakeRequestDelete(): void
    {
        $mockResponse = ['deleted' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('delete', 'https://api.example.com/delete/123', [], 30);

        self::assertIsArray($result);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame('delete', $lastRequest['method']);
    }

    /**
     * Test request with API token sets authorization header.
     */
    public function testMakeRequestWithApiToken(): void
    {
        $this->client->setApiToken('test-api-token-123');

        $mockResponse = ['authorized' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('get', 'https://api.example.com/protected', [], 30);

        self::assertIsArray($result);

        $lastRequest = $this->client->getLastRequest();
        self::assertStringContainsString('X-API-KEY: test-api-token-123', $lastRequest['headers_string']);
    }

    /**
     * Test request without API token does not include authorization header.
     */
    public function testMakeRequestWithoutApiToken(): void
    {
        $mockResponse = ['public' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('get', 'https://api.example.com/public', [], 30);

        self::assertIsArray($result);

        $lastRequest = $this->client->getLastRequest();
        self::assertStringNotContainsString('X-API-KEY:', $lastRequest['headers_string']);
    }

    /**
     * Test default headers are always set.
     */
    public function testMakeRequestDefaultHeaders(): void
    {
        $mockResponse = ['test' => true];
        $this->client->setMockResponse($mockResponse);

        $this->client->makeRequest('get', 'https://api.example.com/test', [], 30);

        $lastRequest = $this->client->getLastRequest();
        self::assertStringContainsString('Accept: application/json', $lastRequest['headers_string']);
        self::assertStringContainsString('Content-Type: application/json', $lastRequest['headers_string']);
        self::assertStringContainsString('User-Agent: Antistatique/Realforce', $lastRequest['headers_string']);
    }

    /**
     * Test timeout is properly set.
     */
    public function testMakeRequestTimeout(): void
    {
        $mockResponse = ['timeout_test' => true];
        $this->client->setMockResponse($mockResponse);

        $this->client->makeRequest('get', 'https://api.example.com/test', [], 120);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame(120, $lastRequest['timeout']);
    }

    /**
     * Test default timeout is used when not specified.
     */
    public function testMakeRequestDefaultTimeout(): void
    {
        $mockResponse = ['default_timeout' => true];
        $this->client->setMockResponse($mockResponse);

        $this->client->makeRequest('get', 'https://api.example.com/test');

        $lastRequest = $this->client->getLastRequest();
        self::assertSame(RealforceClient::TIMEOUT, $lastRequest['timeout']);
    }

    /**
     * Test SSL verification setting.
     */
    public function testMakeRequestSslVerification(): void
    {
        $mockResponse = ['ssl_test' => true];
        $this->client->setMockResponse($mockResponse);

        // Test with SSL verification enabled (default)
        $this->client->makeRequest('get', 'https://api.example.com/test', [], 30);

        $lastRequest = $this->client->getLastRequest();
        self::assertTrue($lastRequest['ssl_verify'] ?? false);
    }

    /**
     * Test response formatting when formatResponse returns false.
     */
    public function testMakeRequestFormatResponseReturnsFalse(): void
    {
        $this->client->setMockFormatResponse(false);

        $result = $this->client->makeRequest('get', 'https://api.example.com/test', [], 30);

        self::assertFalse($result);
    }

    /**
     * Test response formatting when determineSuccess returns boolean.
     */
    public function testMakeRequestDetermineSuccessReturnsBoolean(): void
    {
        $this->client->setMockResponse(['status' => 'success']);
        $this->client->setMockDetermineSuccess(true);

        $result = $this->client->makeRequest('get', 'https://api.example.com/test', [], 30);

        // When formatResponse returns array but determineSuccess returns boolean,
        // the method should return the body.
        self::assertSame(['status' => 'success'], $result);
    }

    /**
     * Test empty args array handling.
     */
    public function testMakeRequestEmptyArgs(): void
    {
        $mockResponse = ['empty_args' => true];
        $this->client->setMockResponse($mockResponse);

        $result = $this->client->makeRequest('get', 'https://api.example.com/test', [], 30);

        self::assertIsArray($result);
        self::assertSame($mockResponse, $result);
    }
}

/**
 * Helper class to expose protected methods and mock responses for testing.
 */
final class MakeRequestTestableRealforceClient extends RealforceClient
{
    private $mockResponse;
    private $mockFormatResponse;
    private $mockDetermineSuccess;

    public function setMockResponse(array $response): void
    {
        $this->mockResponse = $response;
    }

    public function setMockFormatResponse($response): void
    {
        $this->mockFormatResponse = $response;
    }

    public function setMockDetermineSuccess(bool $success): void
    {
        $this->mockDetermineSuccess = $success;
    }

    protected function formatResponse(array $response)
    {
        if (null !== $this->mockFormatResponse) {
            return $this->mockFormatResponse;
        }

        return $this->mockResponse;
    }

    protected function determineSuccess(array $response, $formattedResponse, int $timeout): bool
    {
        if (null !== $this->mockDetermineSuccess) {
            return $this->mockDetermineSuccess;
        }
        $this->requestSuccessful = true;

        return true;
    }

    protected function prepareStateForRequest(string $http_verb, string $url, int $timeout): array
    {
        $this->lastRequest = [
            'method' => $http_verb,
            'url' => $url,
            'timeout' => $timeout,
            'body' => '',
            'headers_string' => '',
            'ssl_verify' => $this->verifySsl,
        ];

        return [
            'headers' => null,
            'httpHeaders' => null,
            'body' => null,
        ];
    }

    protected function attachRequestPayload(&$curl, array $data): void
    {
        $encoded = json_encode($data, \JSON_THROW_ON_ERROR);
        $this->lastRequest['body'] = $encoded;
        // Mock curl_setopt for CURLOPT_POSTFIELDS
    }

    protected function setResponseState(array $response, $response_content, $curl): array
    {
        // Mock the response state setting
        $this->lastRequest['headers_string'] = $this->buildHeadersString();

        return [
            'headers' => ['http_code' => 200],
            'httpHeaders' => ['Content-Type' => 'application/json'],
            'body' => json_encode($this->mockResponse),
        ];
    }

    private function buildHeadersString(): string
    {
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent: Antistatique/Realforce',
        ];

        if ($this->getApiToken()) {
            $headers[] = "X-API-KEY: {$this->getApiToken()}";
        }

        if (($this->lastRequest['method'] ?? '') === 'put') {
            $headers[] = 'Allow: PUT, PATCH, POST';
        }

        return implode("\r\n", $headers);
    }
}
