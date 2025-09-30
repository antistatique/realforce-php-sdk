<?php

namespace Antistatique\Realforce\Tests\Unit\Resource\PublicLabels;

use Antistatique\Realforce\RealforceClient;
use Antistatique\Realforce\Request\I18nRequest;
use Antistatique\Realforce\Resource\AbstractResource;
use Antistatique\Realforce\Resource\PublicLabels;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PublicLabels::class, 'categories')]
#[CoversClass(RealforceClient::class)]
#[CoversClass(AbstractResource::class)]
#[CoversClass(I18nRequest::class)]
final class CategoriesTest extends TestCase
{
    public function testIsAbstractResource(): void
    {
        $rf = new RealforceClient();
        $resource = $rf->publicLabels();
        self::assertInstanceOf(AbstractResource::class, $resource);
    }

    public function test200(): void
    {
        $response = json_decode(file_get_contents(__DIR__.'/../../responses/publicLabels/categories.200.json'), true, 512, JSON_THROW_ON_ERROR);

        $rf_mock = $this->getMockBuilder(RealforceClient::class)
          ->onlyMethods(['makeRequest'])
          ->getMock();

        $rf_mock->expects(self::once())
          ->method('makeRequest')
          ->willReturn($response);

        $query = (new I18nRequest())->lang(['fr']);
        $response = $rf_mock->publicLabels()->categories($query);
        self::assertIsArray($response);
    }
}
