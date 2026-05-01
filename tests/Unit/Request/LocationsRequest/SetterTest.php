<?php

namespace Antistatique\Realforce\Tests\Unit\Request\LocationsRequest;

use Antistatique\Realforce\Request\LocationsRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(LocationsRequest::class, 'isCanton')]
#[CoversMethod(LocationsRequest::class, 'isDistrict')]
#[CoversMethod(LocationsRequest::class, 'isZone')]
#[CoversMethod(LocationsRequest::class, 'isQuarter')]
#[CoversMethod(LocationsRequest::class, 'isCity')]
#[CoversClass(LocationsRequest::class)]
final class SetterTest extends TestCase
{
    /**
     * Test isCanton() sets is_canton in toArray output.
     */
    public function testIsCanton(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->isCanton();

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('is_canton', $arrayResult);
        self::assertSame(1, $arrayResult['is_canton']);
    }

    /**
     * Test isDistrict() sets is_district in toArray output.
     */
    public function testIsDistrict(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->isDistrict();

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('is_district', $arrayResult);
        self::assertSame(1, $arrayResult['is_district']);
    }

    /**
     * Test isZone() sets is_zone in toArray output.
     */
    public function testIsZone(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->isZone();

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('is_zone', $arrayResult);
        self::assertSame(1, $arrayResult['is_zone']);
    }

    /**
     * Test isQuarter() sets is_quarter in toArray output.
     */
    public function testIsQuarter(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->isQuarter();

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('is_quarter', $arrayResult);
        self::assertSame(1, $arrayResult['is_quarter']);
    }

    /**
     * Test isCity() sets is_city in toArray output.
     */
    public function testIsCity(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->isCity();

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('is_city', $arrayResult);
        self::assertSame(1, $arrayResult['is_city']);
    }

    /**
     * Test flags are absent from toArray when not set.
     */
    public function testFlagsAbsentByDefault(): void
    {
        $arrayResult = (new LocationsRequest())->lang(['fr'])->toArray();

        self::assertArrayNotHasKey('is_canton', $arrayResult);
        self::assertArrayNotHasKey('is_district', $arrayResult);
        self::assertArrayNotHasKey('is_zone', $arrayResult);
        self::assertArrayNotHasKey('is_quarter', $arrayResult);
        self::assertArrayNotHasKey('is_city', $arrayResult);
    }

    /**
     * Test all is* flags can be set together via method chaining.
     */
    public function testMethodChaining(): void
    {
        $request = (new LocationsRequest())
            ->lang(['fr'])
            ->isCanton()
            ->isDistrict()
            ->isZone()
            ->isQuarter()
            ->isCity();

        $result = $request->toArray();

        self::assertSame(1, $result['is_canton']);
        self::assertSame(1, $result['is_district']);
        self::assertSame(1, $result['is_zone']);
        self::assertSame(1, $result['is_quarter']);
        self::assertSame(1, $result['is_city']);
    }
}
