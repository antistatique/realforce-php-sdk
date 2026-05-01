<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'getLastResponseHttpStatus')]
#[CoversClass(RealforceClient::class)]
final class GetLastResponseHttpStatusTest extends TestCase
{
    protected GetLastResponseHttpStatusTestableRealforceClient $client;

    protected function setUp(): void
    {
        $this->client = new GetLastResponseHttpStatusTestableRealforceClient();
    }

    /**
     * Test getLastResponseHttpStatus() returns null before any request.
     */
    public function testReturnsNullByDefault(): void
    {
        self::assertNull($this->client->getLastResponseHttpStatus());
    }

    /**
     * Test getLastResponseHttpStatus() returns the HTTP status after a successful request.
     */
    public function testReturnsStatusAfterSuccessfulRequest(): void
    {
        $response = ['headers' => ['http_code' => 200]];
        $this->client->publicDetermineSuccess($response, [], 0);

        self::assertSame(200, $this->client->getLastResponseHttpStatus());
    }

    /**
     * Test getLastResponseHttpStatus() returns the HTTP status after a failed request.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('failureStatusProvider')]
    public function testReturnsStatusAfterFailedRequest(int $httpCode): void
    {
        $response = ['headers' => ['http_code' => $httpCode]];

        try {
            $this->client->publicDetermineSuccess($response, [], 0);
        } catch (\Exception) {
        }

        self::assertSame($httpCode, $this->client->getLastResponseHttpStatus());
    }

    /**
     * Data provider for failure HTTP status codes.
     */
    public static function failureStatusProvider(): iterable
    {
        yield '400 Bad Request' => ['httpCode' => 400];
        yield '401 Unauthorized' => ['httpCode' => 401];
        yield '403 Forbidden' => ['httpCode' => 403];
        yield '404 Not Found' => ['httpCode' => 404];
        yield '500 Internal Server Error' => ['httpCode' => 500];
    }
}

/**
 * Helper class to expose protected methods for testing.
 */
final class GetLastResponseHttpStatusTestableRealforceClient extends RealforceClient
{
    public function publicDetermineSuccess(array $response, array|false $formattedResponse, int $timeout): bool
    {
        return $this->determineSuccess($response, $formattedResponse, $timeout);
    }
}
