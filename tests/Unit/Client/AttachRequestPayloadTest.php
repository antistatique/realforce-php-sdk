<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'attachRequestPayload')]
#[CoversClass(RealforceClient::class)]
final class AttachRequestPayloadTest extends TestCase
{
    /**
     * The Realforce mocked client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new AttachRequestPayloadTestableRealforceClient();
    }

    /**
     * Tests attachRequestPayload with simple data.
     */
    public function testAttachRequestPayloadSimpleData(): void
    {
        $curl = curl_init();
        $data = ['key' => 'value'];

        $this->client->publicAttachRequestPayload($curl, $data);

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('body', $lastRequest);
        self::assertJsonStringEqualsJsonString('{"key":"value"}', $lastRequest['body']);

        $info = curl_getinfo($curl);
        self::assertNotEmpty($info);

        curl_close($curl);
    }

    /**
     * Tests attachRequestPayload with nested data.
     */
    public function testAttachRequestPayloadNestedData(): void
    {
        $curl = curl_init();
        $data = [
            'parent' => [
                'child1' => 'value1',
                'child2' => 'value2',
            ],
        ];

        $this->client->publicAttachRequestPayload($curl, $data);

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('body', $lastRequest);
        self::assertJsonStringEqualsJsonString(
            '{"parent":{"child1":"value1","child2":"value2"}}',
            $lastRequest['body']
        );

        curl_close($curl);
    }

    /**
     * Tests attachRequestPayload with special characters.
     */
    public function testAttachRequestPayloadSpecialChars(): void
    {
        $curl = curl_init();
        $data = [
            'special' => 'test & value > test',
            'unicode' => 'été',
        ];

        $this->client->publicAttachRequestPayload($curl, $data);

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('body', $lastRequest);
        self::assertJson($lastRequest['body']);

        $decoded = json_decode($lastRequest['body'], true);
        self::assertSame('test & value > test', $decoded['special']);
        self::assertSame('été', $decoded['unicode']);

        curl_close($curl);
    }

    /**
     * Tests attachRequestPayload with empty data.
     */
    public function testAttachRequestPayloadEmptyData(): void
    {
        $curl = curl_init();
        $data = [];

        $this->client->publicAttachRequestPayload($curl, $data);

        $lastRequest = $this->client->getLastRequest();
        self::assertArrayHasKey('body', $lastRequest);
        self::assertJsonStringEqualsJsonString('[]', $lastRequest['body']);

        curl_close($curl);
    }
}

/**
 * Helper class to expose protected method for testing.
 */
final class AttachRequestPayloadTestableRealforceClient extends RealforceClient
{
    /**
     * {@inheritdoc}
     */
    public function publicAttachRequestPayload(\CurlHandle &$curl, array $data): void
    {
        $this->attachRequestPayload($curl, $data);
    }
}
