<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'makeRequest')]
#[CoversClass(RealforceClient::class)]
final class AuthorizationHeaderTest extends TestCase
{
    use PHPMock;

    /**
     * An API key of "0" is perfectly valid but a falsy PHP string.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testMakeRequestSendsApiKeyHeaderForFalsyToken(): void
    {
        self::assertContains('X-API-KEY: 0', $this->captureRequestHeaders('0'));
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testMakeRequestOmitsApiKeyHeaderWithoutToken(): void
    {
        foreach ($this->captureRequestHeaders('') as $header) {
            self::assertStringStartsNotWith('X-API-KEY:', $header);
        }
    }

    /**
     * Run makeRequest() with the given token and return CURLOPT_HTTPHEADER.
     *
     * @return array<int, string>
     */
    private function captureRequestHeaders(string $token): array
    {
        $client = $this->getMockBuilder(RealforceClient::class)
            ->onlyMethods(['getApiToken', 'prepareStateForRequest', 'setResponseState', 'formatResponse', 'determineSuccess'])
            ->getMock();
        $client->expects(self::atLeastOnce())->method('getApiToken')->willReturn($token);
        $client->method('prepareStateForRequest')->willReturn([]);
        $client->method('setResponseState')->willReturn([]);
        $client->method('formatResponse')->willReturn(['foo' => 'bar']);
        $client->method('determineSuccess')->willReturn(true);

        $captured = [];
        $curl_setopt_mock = $this->getFunctionMock('Antistatique\\Realforce', 'curl_setopt');
        $curl_setopt_mock->expects(self::atLeastOnce())
            ->willReturnCallback(static function ($curl, int $option, $value) use (&$captured): bool {
                if (\CURLOPT_HTTPHEADER === $option) {
                    $captured = $value;
                }

                return true;
            });

        $curl_exec_mock = $this->getFunctionMock('Antistatique\\Realforce', 'curl_exec');
        $curl_exec_mock->expects(self::once())->willReturn('body');

        $client->makeRequest('get', 'https://api.example.com/endpoint');

        return $captured;
    }
}
