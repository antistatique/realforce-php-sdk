<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'getLastError')]
#[CoversClass(RealforceClient::class)]
final class GetLastErrorTest extends TestCase
{
    protected GetLastErrorTestableRealforceClient $client;

    protected function setUp(): void
    {
        $this->client = new GetLastErrorTestableRealforceClient();
    }

    /**
     * Test getLastError() returns false on a fresh client with no error set.
     */
    public function testReturnsFalseByDefault(): void
    {
        self::assertFalse($this->client->getLastError());
    }

    /**
     * Test getLastError() returns the error string after a timeout.
     */
    public function testReturnsErrorStringAfterTimeout(): void
    {
        $response = ['headers' => ['http_code' => 200, 'total_time' => 15.5]];

        try {
            $this->client->publicDetermineSuccess($response, [], 10);
        } catch (\Exception) {
        }

        self::assertSame('Request timed out after 15.500000 seconds.', $this->client->getLastError());
    }

    /**
     * Test getLastError() returns the error string after a failed request with a message.
     */
    public function testReturnsErrorStringAfterFailureWithMessage(): void
    {
        $response = ['headers' => ['http_code' => 400]];
        $formattedResponse = ['message' => 'Invalid request parameters'];

        try {
            $this->client->publicDetermineSuccess($response, $formattedResponse, 0);
        } catch (\Exception) {
        }

        self::assertSame('400 Invalid request parameters', $this->client->getLastError());
    }

    /**
     * Test getLastError() returns the generic error string after an unknown failure.
     */
    public function testReturnsGenericErrorStringAfterUnknownFailure(): void
    {
        $response = ['headers' => ['http_code' => 500]];

        try {
            $this->client->publicDetermineSuccess($response, [], 0);
        } catch (\Exception) {
        }

        self::assertSame('Unknown error, call getLastResponse() to find out what happened.', $this->client->getLastError());
    }

    /**
     * Test getLastError() returns false again after prepareStateForRequest resets it.
     */
    public function testReturnsFalseAfterStateReset(): void
    {
        $response = ['headers' => ['http_code' => 500]];

        try {
            $this->client->publicDetermineSuccess($response, [], 0);
        } catch (\Exception) {
        }

        self::assertNotFalse($this->client->getLastError());

        // A new request preparation resets the error.
        $this->client->publicPrepareStateForRequest('get', 'https://example.com', 10);
        self::assertFalse($this->client->getLastError());
    }
}

/**
 * Helper class to expose protected methods for testing.
 */
final class GetLastErrorTestableRealforceClient extends RealforceClient
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
