<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'findHttpStatus')]
#[CoversClass(RealforceClient::class)]
final class FindHttpStatusTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new FindHttpStatusTestableRealforceClient();
    }

    /**
     * Tests findHttpStatus with headers.
     */
    public function testFindHttpStatusFromHeaders(): void
    {
        $response = [
            'headers' => [
                'http_code' => 200,
            ],
            'body' => 'some response body',
        ];

        $formattedResponse = ['data' => 'some data'];

        $status = $this->client->publicFindHttpStatus($response, $formattedResponse);
        self::assertSame(200, $status);
    }

    /**
     * Tests findHttpStatus with no status information.
     */
    public function testFindHttpStatusWithNoInformation(): void
    {
        $response = [
            'headers' => [],
            'body' => 'some response body',
        ];

        $formattedResponse = ['data' => 'some data'];

        $status = $this->client->publicFindHttpStatus($response, $formattedResponse);
        self::assertSame(418, $status);
    }

    /**
     * Tests findHttpStatus with empty response.
     */
    public function testFindHttpStatusWithEmptyResponse(): void
    {
        $response = [];
        $formattedResponse = false;

        $status = $this->client->publicFindHttpStatus($response, $formattedResponse);
        self::assertSame(418, $status);
    }

    /**
     * Tests findHttpStatus prioritizes headers over formatted response.
     */
    public function testFindHttpStatusPrioritizesHeaders(): void
    {
        $response = [
            'headers' => [
                'http_code' => 200,
            ],
            'body' => 'some response body',
        ];

        $formattedResponse = ['code' => 201];

        $status = $this->client->publicFindHttpStatus($response, $formattedResponse);
        self::assertSame(200, $status);
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class FindHttpStatusTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicFindHttpStatus(array $response, array|false $formattedResponse): int
    {
        return $this->findHttpStatus($response, $formattedResponse);
    }
}
