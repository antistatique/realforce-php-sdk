<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'formatResponse')]
final class FormatResponseTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new FormatResponseTestableRealforceClient();
    }

    /**
     * Tests formatResponse with valid JSON response.
     */
    public function testFormatResponseValidJson(): void
    {
        $response = [
            'body' => '{"key":"value"}',
        ];

        $result = $this->client->publicFormatResponse($response);

        self::assertIsArray($result);
        self::assertArrayHasKey('key', $result);
        self::assertSame('value', $result['key']);
        self::assertSame($response, $this->client->getLastResponse());
    }

    /**
     * Tests formatResponse with empty body.
     */
    public function testFormatResponseEmptyBody(): void
    {
        $response = [
            'body' => '',
        ];

        $result = $this->client->publicFormatResponse($response);

        self::assertSame([], $result);
        self::assertSame($response, $this->client->getLastResponse());
    }

    /**
     * Tests formatResponse with invalid JSON.
     */
    public function testFormatResponseInvalidJson(): void
    {
        $response = [
            'body' => '{invalid json}',
        ];

        $this->expectException(\JsonException::class);
        $this->client->publicFormatResponse($response);
    }

    /**
     * Tests formatResponse with nested JSON data.
     */
    public function testFormatResponseNestedJson(): void
    {
        $response = [
            'body' => '{"parent":{"child":"value"}}',
        ];

        $result = $this->client->publicFormatResponse($response);

        self::assertIsArray($result);
        self::assertArrayHasKey('parent', $result);
        self::assertIsArray($result['parent']);
        self::assertArrayHasKey('child', $result['parent']);
        self::assertSame('value', $result['parent']['child']);
    }

    /**
     * Tests formatResponse with array JSON.
     */
    public function testFormatResponseArrayJson(): void
    {
        $response = [
            'body' => '["value1", "value2"]',
        ];

        $result = $this->client->publicFormatResponse($response);

        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertSame('value1', $result[0]);
        self::assertSame('value2', $result[1]);
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class FormatResponseTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicFormatResponse(array $response): array|false
    {
        return $this->formatResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLastResponse(): array
    {
        return $this->lastResponse;
    }
}
