<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'success')]
#[CoversClass(RealforceClient::class)]
final class SuccessTest extends TestCase
{
    protected SuccessTestableRealforceClient $client;

    protected function setUp(): void
    {
        $this->client = new SuccessTestableRealforceClient();
    }

    /**
     * Test success() returns false on a fresh client before any request.
     */
    public function testReturnsFalseByDefault(): void
    {
        self::assertFalse($this->client->success());
    }

    /**
     * Test success() returns true after a 2xx response is processed.
     */
    public function testReturnsTrueAfterSuccessfulRequest(): void
    {
        $response = ['headers' => ['http_code' => 200]];
        $this->client->publicDetermineSuccess($response, [], 0);

        self::assertTrue($this->client->success());
    }

    /**
     * Test success() returns false again after prepareStateForRequest resets it.
     */
    public function testReturnsFalseAfterStateReset(): void
    {
        // Bring the client into a successful state first.
        $response = ['headers' => ['http_code' => 200]];
        $this->client->publicDetermineSuccess($response, [], 0);
        self::assertTrue($this->client->success());

        // A new request preparation resets the flag.
        $this->client->publicPrepareStateForRequest('get', 'https://example.com', 10);
        self::assertFalse($this->client->success());
    }
}

/**
 * Helper class to expose protected methods for testing.
 */
final class SuccessTestableRealforceClient extends RealforceClient
{
    public function publicDetermineSuccess(array $response, array|false $formattedResponse, int $timeout): bool
    {
        return $this->determineSuccess($response, $formattedResponse, $timeout);
    }

    public function publicPrepareStateForRequest(string $http_verb, string $url, int $timeout): array
    {
        return $this->prepareStateForRequest($http_verb, $url, $timeout);
    }
}
