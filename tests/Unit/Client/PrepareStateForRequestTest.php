<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'prepareStateForRequest')]
final class PrepareStateForRequestTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new PrepareStateForRequestTestableRealforceClient();
    }

    /**
     * Tests prepareStateForRequest with GET request.
     */
    public function testPrepareStateForRequestGet(): void
    {
        $response = $this->client->publicPrepareStateForRequest('get', 'https://api.example.com/endpoint', 10);

        // Test response structure.
        self::assertIsArray($response);
        self::assertArrayHasKey('headers', $response);
        self::assertArrayHasKey('httpHeaders', $response);
        self::assertArrayHasKey('body', $response);
        self::assertNull($response['headers']);
        self::assertNull($response['httpHeaders']);
        self::assertNull($response['body']);

        // Test lastRequest properties.
        $lastRequest = $this->client->getLastRequest();
        self::assertIsArray($lastRequest);
        self::assertArrayHasKey('scheme', $lastRequest);
        self::assertArrayHasKey('host', $lastRequest);
        self::assertArrayHasKey('path', $lastRequest);
        self::assertArrayHasKey('method', $lastRequest);
        self::assertSame('get', $lastRequest['method']);
        self::assertSame(10, $lastRequest['timeout']);
        self::assertSame('', $lastRequest['body']);

        // Test state reset.
        self::assertSame('', $this->client->getLastError());
        self::assertFalse($this->client->getRequestSuccessful());
    }

    /**
     * Tests prepareStateForRequest with POST request.
     */
    public function testPrepareStateForRequestPost(): void
    {
        $this->client->publicPrepareStateForRequest('post', 'https://api.example.com/endpoint', 30);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame('post', $lastRequest['method']);
        self::assertSame(30, $lastRequest['timeout']);
    }

    /**
     * Tests prepareStateForRequest with different URL formats.
     */
    public function testPrepareStateForRequestUrlParsing(): void
    {
        // Test with query parameters.
        $this->client->publicPrepareStateForRequest(
            'get',
            'https://api.example.com/endpoint?param=value',
            10
        );

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('query', $lastRequest);
        self::assertSame('param=value', $lastRequest['query']);

        // Test with port number.
        $this->client->publicPrepareStateForRequest(
            'get',
            'https://api.example.com:8080/endpoint',
            10
        );

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('port', $lastRequest);
        self::assertSame(8080, $lastRequest['port']);
    }

    /**
     * Tests prepareStateForRequest with different timeouts.
     */
    public function testPrepareStateForRequestTimeouts(): void
    {
        // Test with zero timeout.
        $this->client->publicPrepareStateForRequest('get', 'https://api.example.com', 0);
        $lastRequest = $this->client->getLastRequest();
        self::assertSame(0, $lastRequest['timeout']);

        // Test with maximum timeout.
        $this->client->publicPrepareStateForRequest('get', 'https://api.example.com', \PHP_INT_MAX);
        $lastRequest = $this->client->getLastRequest();
        self::assertSame(\PHP_INT_MAX, $lastRequest['timeout']);
    }

    /**
     * Tests prepareStateForRequest resets all state properly.
     */
    public function testPrepareStateForRequestStateReset(): void
    {
        $this->client->publicPrepareStateForRequest('get', 'https://api.example.com', 10);

        // Verify lastResponse is reset.
        $lastResponse = $this->client->getLastResponse();
        self::assertNull($lastResponse['headers']);
        self::assertNull($lastResponse['httpHeaders']);
        self::assertNull($lastResponse['body']);

        // Verify error state is reset.
        self::assertSame('', $this->client->getLastError());

        // Verify request state is reset.
        self::assertFalse($this->client->getRequestSuccessful());
    }

    /**
     * Tests prepareStateForRequest with various HTTP methods.
     *
     * @dataProvider httpMethodsProvider
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('httpMethodsProvider')]
    public function testPrepareStateForRequestHttpMethods(string $method): void
    {
        $this->client->publicPrepareStateForRequest($method, 'https://api.example.com', 10);

        $lastRequest = $this->client->getLastRequest();
        self::assertSame($method, $lastRequest['method']);
    }

    /**
     * Data provider for HTTP methods.
     */
    public static function httpMethodsProvider(): iterable
    {
        yield 'get' => ['get'];
        yield 'post' => ['post'];
        yield 'put' => ['put'];
        yield 'patch' => ['patch'];
        yield 'delete' => ['delete'];
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class PrepareStateForRequestTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicPrepareStateForRequest(string $http_verb, string $url, int $timeout): array
    {
        return $this->prepareStateForRequest($http_verb, $url, $timeout);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestSuccessful(): bool
    {
        return $this->requestSuccessful;
    }
}
