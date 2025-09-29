<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'determineSuccess')]
final class DetermineSuccessTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new DetermineSuccessTestableRealforceClient();
    }

    /**
     * Test HTTP status codes that indicate failure.
     */
    public function testDetermineSuccessHttpStatusFail(): void
    {
        $response = ['headers' => ['http_code' => 400]];
        $formattedResponse = [];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown error, call getLastResponse() to find out what happened.');

        $this->client->publicDetermineSuccess($response, $formattedResponse, 0);
        self::assertFalse($this->client->getRequestSuccessful());
    }

    /**
     * Test HTTP status error with inline details.
     */
    public function testDetermineSuccessHttpStatusFailWithDetails(): void
    {
        $response = ['headers' => ['http_code' => 404]];
        $formattedResponse = [
            'You have insufficient privileges to call this end point',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown error, call getLastResponse() to find out what happened.');

        $this->client->publicDetermineSuccess($response, $formattedResponse, 0);
        self::assertFalse($this->client->getRequestSuccessful());
    }

    /**
     * Test HTTP status error with additional message details.
     */
    public function testDetermineSuccessHttpStatusFailWithMessageDetails(): void
    {
        $response = ['headers' => ['http_code' => 404]];
        $formattedResponse = [
            'message' => ['Lead created successfully'],
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown error, call getLastResponse() to find out what happened.');

        $this->client->publicDetermineSuccess($response, $formattedResponse, 0);
        self::assertFalse($this->client->getRequestSuccessful());
    }

    /**
     * Test HTTP status codes that indicate success.
     */
    public function testDetermineSuccessHttpStatusSuccess(): void
    {
        $response = ['headers' => ['http_code' => 200]];
        $formattedResponse = [];

        $result = $this->client->publicDetermineSuccess($response, $formattedResponse, 0);

        self::assertTrue($result);
        self::assertTrue($this->client->getRequestSuccessful());
    }

    /**
     * Test timeout scenario.
     */
    public function testDetermineSuccessTimeout(): void
    {
        $timeout = 10;
        $response = [
            'headers' => [
                'http_code' => 200,
                'total_time' => 15.5,
            ],
        ];
        $formattedResponse = [];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Request timed out after 15.500000 seconds.');

        $this->client->publicDetermineSuccess($response, $formattedResponse, $timeout);
        self::assertFalse($this->client->getRequestSuccessful());
    }

    /**
     * Test unknown error scenario.
     */
    public function testDetermineSuccessUnknownError(): void
    {
        $response = ['headers' => ['http_code' => 500]];
        $formattedResponse = [];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown error, call getLastResponse() to find out what happened.');

        $this->client->publicDetermineSuccess($response, $formattedResponse, 0);
        self::assertFalse($this->client->getRequestSuccessful());
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class DetermineSuccessTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicDetermineSuccess(array $response, array|false $formattedResponse, int $timeout): bool
    {
        return $this->determineSuccess($response, $formattedResponse, $timeout);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestSuccessful(): bool
    {
        return $this->requestSuccessful;
    }
}
