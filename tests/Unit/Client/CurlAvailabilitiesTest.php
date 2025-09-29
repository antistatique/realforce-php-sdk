<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, 'isCurlAvailable')]
final class CurlAvailabilitiesTest extends TestCase
{
    use PHPMock;

    public function testIsCurlAvailable(): void
    {
        $realforce = new RealforceClient();
        $this->assertTrue($realforce->isCurlAvailable());
    }

    public function testCurlNotAvailable(): void
    {
        $realforceMock = $this->createMock(RealforceClient::class);
        $realforceMock->method('isCurlAvailable')->willReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('cURL support is required, but can\'t be found.');
        $realforceMock->__construct();

        $realforceMock->method('isCurlAvailable')->willReturn(true);
    }

    #[DoesNotPerformAssertions]
    public function testCurlAvailable(): void
    {
        $realforceMock = $this->createMock(RealforceClient::class);
        $realforceMock->method('isCurlAvailable')->willReturn(true);
        $realforceMock->__construct();
    }
}
