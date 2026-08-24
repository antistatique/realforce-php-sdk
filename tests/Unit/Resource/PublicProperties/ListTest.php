<?php

namespace Antistatique\Realforce\Tests\Unit\Resource\PublicProperties;

use Antistatique\Realforce\RealforceClient;
use Antistatique\Realforce\Request\PropertiesListRequest;
use Antistatique\Realforce\Resource\AbstractResource;
use Antistatique\Realforce\Resource\PublicProperties;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PublicProperties::class, 'list')]
#[CoversClass(RealforceClient::class)]
#[CoversClass(AbstractResource::class)]
#[CoversClass(PropertiesListRequest::class)]
final class ListTest extends TestCase
{
    public function testIsAbstractResource(): void
    {
        $rf = new RealforceClient();
        $resource = $rf->publicProperties();
        self::assertInstanceOf(AbstractResource::class, $resource);
    }

    public function test200(): void
    {
        $response = json_decode(file_get_contents(__DIR__.'/../../responses/publicProperties/200.json'), true, 512, JSON_THROW_ON_ERROR);

        $query = new PropertiesListRequest();

        $rf_mock = $this->getMockBuilder(RealforceClient::class)
          ->onlyMethods(['makeRequest'])
          ->getMock();

        $rf_mock->expects(self::once())
          ->method('makeRequest')
          ->with('get', 'https://listings.realforce.ch/api/v1/get-full-listings', $query->toArray(), RealforceClient::TIMEOUT)
          ->willReturn($response);

        $response = $rf_mock->publicProperties()->list($query);
        self::assertIsArray($response);
    }
}
