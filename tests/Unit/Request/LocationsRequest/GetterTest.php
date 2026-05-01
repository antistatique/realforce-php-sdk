<?php

namespace Antistatique\Realforce\Tests\Unit\Request\LocationsRequest;

use Antistatique\Realforce\Request\LocationsRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(LocationsRequest::class, 'cantonId')]
#[CoversMethod(LocationsRequest::class, 'districtId')]
#[CoversMethod(LocationsRequest::class, 'zoneId')]
#[CoversMethod(LocationsRequest::class, 'quarterId')]
#[CoversMethod(LocationsRequest::class, 'cityId')]
#[CoversClass(LocationsRequest::class)]
final class GetterTest extends TestCase
{
    /**
     * Test cantonId() sets canton_id in toArray output.
     */
    public function testCantonId(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->cantonId(42);

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('canton_id', $arrayResult);
        self::assertSame(42, $arrayResult['canton_id']);
    }

    /**
     * Test districtId() sets district_id in toArray output.
     */
    public function testDistrictId(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->districtId(7);

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('district_id', $arrayResult);
        self::assertSame(7, $arrayResult['district_id']);
    }

    /**
     * Test zoneId() sets zone_id in toArray output.
     */
    public function testZoneId(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->zoneId(99);

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('zone_id', $arrayResult);
        self::assertSame(99, $arrayResult['zone_id']);
    }

    /**
     * Test quarterId() sets quarter_id in toArray output.
     */
    public function testQuarterId(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->quarterId(303);

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('quarter_id', $arrayResult);
        self::assertSame(303, $arrayResult['quarter_id']);
    }

    /**
     * Test cityId() sets city_id in toArray output.
     */
    public function testCityId(): void
    {
        $request = new LocationsRequest();
        $result = $request->lang(['fr'])->cityId(512);

        self::assertInstanceOf(LocationsRequest::class, $result);
        self::assertSame($request, $result);

        $arrayResult = $request->toArray();
        self::assertArrayHasKey('city_id', $arrayResult);
        self::assertSame(512, $arrayResult['city_id']);
    }

    /**
     * Test ID filters are absent from toArray when not set.
     */
    public function testIdsAbsentByDefault(): void
    {
        $arrayResult = (new LocationsRequest())->lang(['fr'])->toArray();

        self::assertArrayNotHasKey('canton_id', $arrayResult);
        self::assertArrayNotHasKey('district_id', $arrayResult);
        self::assertArrayNotHasKey('zone_id', $arrayResult);
        self::assertArrayNotHasKey('quarter_id', $arrayResult);
        self::assertArrayNotHasKey('city_id', $arrayResult);
    }

    /**
     * Test multiple calls overwrite the previous value for each ID filter.
     */
    public function testCallsOverwrite(): void
    {
        $request = (new LocationsRequest())->lang(['fr'])->cantonId(1);
        self::assertSame(1, $request->toArray()['canton_id']);

        $request->cantonId(99);
        self::assertSame(99, $request->toArray()['canton_id']);
    }

    /**
     * Test all ID filters can be set together via method chaining.
     */
    public function testMethodChaining(): void
    {
        $request = (new LocationsRequest())
            ->lang(['fr'])
            ->cantonId(1)
            ->districtId(2)
            ->zoneId(3)
            ->quarterId(4)
            ->cityId(5);

        $result = $request->toArray();

        self::assertSame(1, $result['canton_id']);
        self::assertSame(2, $result['district_id']);
        self::assertSame(3, $result['zone_id']);
        self::assertSame(4, $result['quarter_id']);
        self::assertSame(5, $result['city_id']);
    }
}
