<?php

namespace Antistatique\Realforce\Tests\Unit\Resource\PublicLabels;

use Antistatique\Realforce\RealforceClient;
use Antistatique\Realforce\Request\LocationsRequest;
use Antistatique\Realforce\Resource\AbstractResource;
use Antistatique\Realforce\Resource\PublicLabels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PublicLabels::class, 'locations')]
#[CoversClass(RealforceClient::class)]
#[CoversClass(AbstractResource::class)]
#[CoversClass(LocationsRequest::class)]
final class LocationsTest extends TestCase
{
    public function testIsAbstractResource(): void
    {
        $rf = new RealforceClient();
        $resource = $rf->publicLabels();
        self::assertInstanceOf(AbstractResource::class, $resource);
    }

    public function test200(): void
    {
        $response = json_decode(file_get_contents(__DIR__.'/../../responses/publicLabels/locations.200.json'), true, 512, JSON_THROW_ON_ERROR);

        $query = (new LocationsRequest())->lang(['fr']);

        $rf_mock = $this->getMockBuilder(RealforceClient::class)
          ->onlyMethods(['makeRequest'])
          ->getMock();

        $rf_mock->expects(self::once())
          ->method('makeRequest')
          ->with('get', 'https://labels.realforce.ch/api/v1/get-locations', $query->toArray(), RealforceClient::TIMEOUT)
          ->willReturn($response);

        $response = $rf_mock->publicLabels()->locations($query);
        self::assertIsArray($response);
    }
}
