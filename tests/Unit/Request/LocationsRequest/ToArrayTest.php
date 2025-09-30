<?php

namespace Antistatique\Realforce\Tests\Unit\Request\LocationsRequest;

use Antistatique\Realforce\Request\LocationsRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(LocationsRequest::class, 'toArray')]
#[CoversClass(LocationsRequest::class)]
final class ToArrayTest extends TestCase
{
    /**
     * Test toArray with different configurations.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('languageProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('isCountryProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('isCantonProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('isDistrictProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('isZoneProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('isQuarterProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('isCityProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('countryProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('cantonProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('districtProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('zoneProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('quarterProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('cityProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('locationsProvider')]
    public function testToArray(LocationsRequest $request, array $expected): void
    {
        $result = $request->toArray();
        self::assertSame($expected, $result);
    }

    /**
     * Data provider for language test cases.
     */
    public static function languageProvider(): iterable
    {
        yield 'single language' => [
            'request' => (new LocationsRequest())->lang(['fr']),
            'expected' => ['lang' => 'fr'],
        ];

        yield 'multiple languages' => [
            'request' => (new LocationsRequest())->lang(['fr', 'en']),
            'expected' => ['lang' => 'fr|en'],
        ];

        yield 'empty language array' => [
            'request' => (new LocationsRequest())->lang([]),
            'expected' => ['lang' => ''],
        ];
    }

    /**
     * Data provider for isCountry test cases.
     */
    public static function isCountryProvider(): iterable
    {
        yield 'isCountry On' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isCountry(),
            'expected' => ['lang' => 'fr', 'is_country' => 1],
        ];
    }

    /**
     * Data provider for isCanton test cases.
     */
    public static function isCantonProvider(): iterable
    {
        yield 'isCanton On' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isCanton(),
            'expected' => ['lang' => 'fr', 'is_canton' => 1],
        ];
    }

    /**
     * Data provider for isDistrict test cases.
     */
    public static function isDistrictProvider(): iterable
    {
        yield 'isDistrict On' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isDistrict(),
            'expected' => ['lang' => 'fr', 'is_district' => 1],
        ];
    }

    /**
     * Data provider for isZone test cases.
     */
    public static function isZoneProvider(): iterable
    {
        yield 'isZone On' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isZone(),
            'expected' => ['lang' => 'fr', 'is_zone' => 1],
        ];
    }

    /**
     * Data provider for isQuarter test cases.
     */
    public static function isQuarterProvider(): iterable
    {
        yield 'isQuarter On' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isQuarter(),
            'expected' => ['lang' => 'fr', 'is_quarter' => 1],
        ];
    }

    /**
     * Data provider for isCity test cases.
     */
    public static function isCityProvider(): iterable
    {
        yield 'isCity On' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isCity(),
            'expected' => ['lang' => 'fr', 'is_city' => 1],
        ];
    }

    /**
     * Data provider for country filter test cases.
     */
    public static function countryProvider(): iterable
    {
        yield 'country Identifier' => [
            'request' => (new LocationsRequest())->lang(['fr'])->countryId(123),
            'expected' => ['lang' => 'fr', 'country_id' => 123],
        ];

        yield 'country zero value' => [
            'request' => (new LocationsRequest())->lang(['fr'])->countryId(0),
            'expected' => ['lang' => 'fr', 'country_id' => 0],
        ];
    }

    /**
     * Data provider for canton filter test cases.
     */
    public static function cantonProvider(): iterable
    {
        yield 'canton Identifier' => [
            'request' => (new LocationsRequest())->lang(['fr'])->cantonId(789),
            'expected' => ['lang' => 'fr', 'canton_id' => 789],
        ];

        yield 'canton zero value' => [
            'request' => (new LocationsRequest())->lang(['fr'])->cantonId(0),
            'expected' => ['lang' => 'fr', 'canton_id' => 0],
        ];
    }

    /**
     * Data provider for district filter test cases.
     */
    public static function districtProvider(): iterable
    {
        yield 'district Identifier' => [
            'request' => (new LocationsRequest())->lang(['fr'])->districtId(9),
            'expected' => ['lang' => 'fr', 'district_id' => 9],
        ];

        yield 'district zero value' => [
            'request' => (new LocationsRequest())->lang(['fr'])->districtId(0),
            'expected' => ['lang' => 'fr', 'district_id' => 0],
        ];
    }

    /**
     * Data provider for zone filter test cases.
     */
    public static function zoneProvider(): iterable
    {
        yield 'zone Identifier' => [
            'request' => (new LocationsRequest())->lang(['fr'])->zoneId(51),
            'expected' => ['lang' => 'fr', 'zone_id' => 51],
        ];

        yield 'zone zero value' => [
            'request' => (new LocationsRequest())->lang(['fr'])->zoneId(0),
            'expected' => ['lang' => 'fr', 'zone_id' => 0],
        ];
    }

    /**
     * Data provider for quarter filter test cases.
     */
    public static function quarterProvider(): iterable
    {
        yield 'quarter Identifier' => [
            'request' => (new LocationsRequest())->lang(['fr'])->quarterId(606),
            'expected' => ['lang' => 'fr', 'quarter_id' => 606],
        ];

        yield 'quarter zero value' => [
            'request' => (new LocationsRequest())->lang(['fr'])->quarterId(0),
            'expected' => ['lang' => 'fr', 'quarter_id' => 0],
        ];
    }

    /**
     * Data provider for city filter test cases.
     */
    public static function cityProvider(): iterable
    {
        yield 'city Identifier' => [
            'request' => (new LocationsRequest())->lang(['fr'])->cityId(606),
            'expected' => ['lang' => 'fr', 'city_id' => 606],
        ];

        yield 'city zero value' => [
            'request' => (new LocationsRequest())->lang(['fr'])->cityId(0),
            'expected' => ['lang' => 'fr', 'city_id' => 0],
        ];
    }

    /**
     * Data provider for mixed configuration test cases.
     */
    public static function locationsProvider(): iterable
    {
        yield 'multiple is flags' => [
            'request' => (new LocationsRequest())->lang(['fr', 'en'])->isCountry()->isCanton(),
            'expected' => ['lang' => 'fr|en', 'is_country' => 1, 'is_canton' => 1],
        ];

        yield 'is flag with filter' => [
            'request' => (new LocationsRequest())->lang(['fr'])->isCountry()->countryId(123),
            'expected' => ['lang' => 'fr', 'is_country' => 1, 'country_id' => 123],
        ];

        yield 'multiple filters' => [
            'request' => (new LocationsRequest())->lang(['en'])->countryId(123)->cantonId(456),
            'expected' => ['lang' => 'en', 'country_id' => 123, 'canton_id' => 456],
        ];

        yield 'all options combined' => [
            'request' => (new LocationsRequest())
                ->lang(['fr', 'en', 'de'])
                ->isCountry()
                ->isCanton()
                ->isDistrict()
                ->isZone()
                ->isCity()
                ->isQuarter()
                ->countryId(1)
                ->cantonId(2)
                ->districtId(3)
                ->zoneId(4)
                ->quarterId(5),
            'expected' => [
                'lang' => 'fr|en|de',
                'is_country' => 1,
                'is_canton' => 1,
                'is_district' => 1,
                'is_zone' => 1,
                'is_quarter' => 1,
                'is_city' => 1,
                'country_id' => 1,
                'canton_id' => 2,
                'district_id' => 3,
                'zone_id' => 4,
                'quarter_id' => 5,
            ],
        ];
    }

    public function testMandatoryProperties(): void
    {
        $request = new LocationsRequest();
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Typed property Antistatique\Realforce\Request\LocationsRequest::$lang must not be accessed before initialization');
        $request->toArray();
    }
}
