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
final class CurlInitFailureTest extends TestCase
{
    use PHPMock;

    /**
     * curl_init() returns FALSE when a session cannot be allocated.
     *
     * Without the guard the FALSE flows straight into curl_setopt() and the
     * request dies on a TypeError instead of a describable error.
     *
     * php-mock cannot intercept a call site another test in this process has
     * already executed, so this runs in its own process.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testMakeRequestThrowsWhenCurlCannotBeInitialised(): void
    {
        $curl_init_mock = $this->getFunctionMock('Antistatique\\Realforce', 'curl_init');
        $curl_init_mock->expects(self::once())->willReturn(false);

        $client = new RealforceClient();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to initialise a cURL session.');

        try {
            $client->makeRequest('get', 'https://api.example.com/endpoint');
        } finally {
            self::assertSame('Unable to initialise a cURL session.', $client->getLastError());
        }
    }
}
