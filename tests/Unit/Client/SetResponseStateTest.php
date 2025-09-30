<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'setResponseState')]
#[CoversClass(RealforceClient::class)]
final class SetResponseStateTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new SetResponseStateTestableRealforceClient();
    }

    /**
     * Tests setResponseState with valid response content.
     */
    public function testSetResponseStateValidContent(): void
    {
        $curl = curl_init();
        $response = [
            'headers' => [
                'header_size' => 71,
            ],
        ];
        $response_content = "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nContent-Length: 13\r\n\r\n{\"key\":\"value\"}";

        $result = $this->client->publicSetResponseState($response, $response_content, $curl);

        self::assertIsArray($result);
        self::assertArrayHasKey('httpHeaders', $result);
        self::assertArrayHasKey('body', $result);
        self::assertIsArray($result['httpHeaders']);
        self::assertArrayHasKey('Content-Type', $result['httpHeaders']);
        self::assertStringContainsString('{"key":"value"}', $result['body']);

        curl_close($curl);
    }

    /**
     * Tests setResponseState with invalid response content.
     */
    public function testSetResponseStateInvalidContent(): void
    {
        $curl = curl_init();
        $response = ['headers' => ['header_size' => 0]];

        $this->expectException(\Exception::class);
        $this->client->publicSetResponseState($response, false, $curl);

        curl_close($curl);
    }

    /**
     * Tests setResponseState with request headers.
     */
    public function testSetResponseStateWithRequestHeaders(): void
    {
        $curl = curl_init();
        $response = [
            'headers' => [
                'header_size' => 90,
                'request_header' => 'GET /endpoint HTTP/1.1',
            ],
        ];
        $response_content = "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\n\r\n{}";

        $this->client->publicSetResponseState($response, $response_content, $curl);

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('headers', $lastRequest);
        self::assertSame('GET /endpoint HTTP/1.1', $lastRequest['headers']);

        curl_close($curl);
    }

    /**
     * Tests setResponseState with empty response content.
     */
    public function testSetResponseStateEmptyContent(): void
    {
        $curl = curl_init();
        $response = [
            'headers' => [
                'header_size' => 0,
            ],
        ];
        $response_content = '';

        $result = $this->client->publicSetResponseState($response, $response_content, $curl);

        self::assertIsArray($result);
        self::assertEmpty($result['body']);
        self::assertEmpty($result['httpHeaders']);

        curl_close($curl);
    }

    /**
     * Tests setResponseState with multiple headers.
     */
    public function testSetResponseStateMultipleHeaders(): void
    {
        $curl = curl_init();
        $response = [
            'headers' => [
                'header_size' => 89,
            ],
        ];
        $response_content = "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nAuthorization: Bearer token\r\nAccept: */*\r\n\r\n{\"data\":\"test\"}";

        $result = $this->client->publicSetResponseState($response, $response_content, $curl);

        self::assertIsArray($result['httpHeaders']);
        self::assertArrayHasKey('Content-Type', $result['httpHeaders']);
        self::assertArrayHasKey('Authorization', $result['httpHeaders']);
        self::assertArrayHasKey('Accept', $result['httpHeaders']);
        self::assertStringContainsString('{"data":"test"}', $result['body']);

        curl_close($curl);
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class SetResponseStateTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicSetResponseState(array $response, bool|string $response_content, \CurlHandle $curl): array
    {
        return $this->setResponseState($response, $response_content, $curl);
    }
}
