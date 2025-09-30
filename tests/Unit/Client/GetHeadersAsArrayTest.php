<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'getHeadersAsArray')]
#[CoversClass(RealforceClient::class)]
final class GetHeadersAsArrayTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new GetHeadersAsArrayTestableRealforceClient();
    }

    /**
     * Tests getHeadersAsArray with simple headers.
     */
    public function testGetHeadersAsArraySimple(): void
    {
        $headers = "Content-Type: application/json\r\nAccept: */*";

        $result = $this->client->publicGetHeadersAsArray($headers);

        self::assertIsArray($result);
        self::assertArrayHasKey('Content-Type', $result);
        self::assertArrayHasKey('Accept', $result);
        self::assertSame('application/json', $result['Content-Type']);
        self::assertSame('*/*', $result['Accept']);
    }

    /**
     * Tests getHeadersAsArray with HTTP status line.
     */
    public function testGetHeadersAsArrayWithHttpStatus(): void
    {
        $headers = "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nAccept: */*";

        $result = $this->client->publicGetHeadersAsArray($headers);

        self::assertIsArray($result);
        self::assertArrayHasKey('Content-Type', $result);
        self::assertArrayNotHasKey('HTTP/1.1', $result);
    }

    /**
     * Tests getHeadersAsArray with empty lines.
     */
    public function testGetHeadersAsArrayWithEmptyLines(): void
    {
        $headers = "Content-Type: application/json\r\n\r\nAccept: */*\r\n\r\n";

        $result = $this->client->publicGetHeadersAsArray($headers);

        self::assertIsArray($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('Content-Type', $result);
        self::assertArrayHasKey('Accept', $result);
    }

    /**
     * Tests getHeadersAsArray with multiple values.
     */
    public function testGetHeadersAsArrayMultipleValues(): void
    {
        $headers = "Cache-Control: no-cache, no-store\r\nContent-Type: application/json";

        $result = $this->client->publicGetHeadersAsArray($headers);

        self::assertIsArray($result);
        self::assertArrayHasKey('Cache-Control', $result);
        self::assertArrayHasKey('Content-Type', $result);
        self::assertSame('no-cache, no-store', $result['Cache-Control']);
    }

    /**
     * Tests getHeadersAsArray with empty string.
     */
    public function testGetHeadersAsArrayEmptyString(): void
    {
        $headers = '';

        $result = $this->client->publicGetHeadersAsArray($headers);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class GetHeadersAsArrayTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicGetHeadersAsArray(string $headersAsString): array
    {
        return $this->getHeadersAsArray($headersAsString);
    }
}
