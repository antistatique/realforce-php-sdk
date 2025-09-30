<?php

namespace Antistatique\Realforce\Tests\Unit\Request\PropertiesListRequest;

use Antistatique\Realforce\Request\PropertiesListRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PropertiesListRequest::class, 'toArray')]
#[CoversClass(PropertiesListRequest::class)]
final class ToArrayTest extends TestCase
{
    /**
     * Test toArray with different configurations.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('perPageProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('pageProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('languageProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('updatedAfterProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('updatedBeforeProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('propertiesListProvider')]
    public function testToArray(PropertiesListRequest $request, array $expected): void
    {
        $result = $request->toArray();
        self::assertSame($expected, $result);
    }

    /**
     * Data provider for perPage test cases.
     */
    public static function perPageProvider(): iterable
    {
        yield 'default perPage' => [
            'request' => new PropertiesListRequest(),
            'expected' => ['per_page' => 100, 'page' => 0],
        ];

        yield 'custom perPage 50' => [
            'request' => (new PropertiesListRequest())->perPage(50),
            'expected' => ['per_page' => 50, 'page' => 0],
        ];

        yield 'negative perPage' => [
            'request' => (new PropertiesListRequest())->perPage(-4),
            'expected' => ['per_page' => -4, 'page' => 0],
        ];

        yield 'minimum perPage 1' => [
            'request' => (new PropertiesListRequest())->perPage(1),
            'expected' => ['per_page' => 1, 'page' => 0],
        ];

        yield 'maximum perPage 100' => [
            'request' => (new PropertiesListRequest())->perPage(100),
            'expected' => ['per_page' => 100, 'page' => 0],
        ];
    }

    /**
     * Data provider for page test cases.
     */
    public static function pageProvider(): iterable
    {
        yield 'default page' => [
            'request' => new PropertiesListRequest(),
            'expected' => ['per_page' => 100, 'page' => 0],
        ];

        yield 'page 1' => [
            'request' => (new PropertiesListRequest())->page(1),
            'expected' => ['per_page' => 100, 'page' => 1],
        ];

        yield 'page 10' => [
            'request' => (new PropertiesListRequest())->page(10),
            'expected' => ['per_page' => 100, 'page' => 10],
        ];

        yield 'page zero' => [
            'request' => (new PropertiesListRequest())->page(0),
            'expected' => ['per_page' => 100, 'page' => 0],
        ];

        yield 'page negative' => [
            'request' => (new PropertiesListRequest())->page(-5),
            'expected' => ['per_page' => 100, 'page' => -5],
        ];
    }

    /**
     * Data provider for language test cases.
     */
    public static function languageProvider(): iterable
    {
        yield 'single language' => [
            'request' => (new PropertiesListRequest())->lang(['fr']),
            'expected' => ['per_page' => 100, 'page' => 0, 'lang' => 'fr'],
        ];

        yield 'multiple languages' => [
            'request' => (new PropertiesListRequest())->lang(['fr', 'en']),
            'expected' => ['per_page' => 100, 'page' => 0, 'lang' => 'fr|en'],
        ];

        yield 'empty language array' => [
            'request' => (new PropertiesListRequest())->lang([]),
            'expected' => ['per_page' => 100, 'page' => 0, 'lang' => ''],
        ];
    }

    /**
     * Data provider for updatedAfter test cases.
     */
    public static function updatedAfterProvider(): iterable
    {
        yield 'updatedAfter date' => [
            'request' => (new PropertiesListRequest())->updatedAfter(new \DateTime('2023-01-15')),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_min' => '2023-01-15'],
        ];

        yield 'updatedAfter with time' => [
            'request' => (new PropertiesListRequest())->updatedAfter(new \DateTime('2023-12-25 14:30:00')),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_min' => '2023-12-25'],
        ];

        yield 'updatedAfter immutable' => [
            'request' => (new PropertiesListRequest())->updatedAfter(new \DateTimeImmutable('2023-06-01')),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_min' => '2023-06-01'],
        ];

        yield 'updatedAfter with timezone at midnight' => [
            'request' => (new PropertiesListRequest())->updatedAfter(new \DateTime('2023-06-01 00:00:00', new \DateTimeZone('America/New_York'))),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_min' => '2023-06-01'],
        ];

        yield 'updatedAfter with UTC timezone at midnight' => [
            'request' => (new PropertiesListRequest())->updatedAfter(new \DateTime('2023-06-01 00:00:00', new \DateTimeZone('UTC'))),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_min' => '2023-06-01'],
        ];
    }

    /**
     * Data provider for updatedBefore test cases.
     */
    public static function updatedBeforeProvider(): iterable
    {
        yield 'updatedBefore date' => [
            'request' => (new PropertiesListRequest())->updatedBefore(new \DateTime('2023-12-31')),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_max' => '2023-12-31'],
        ];

        yield 'updatedBefore with time' => [
            'request' => (new PropertiesListRequest())->updatedBefore(new \DateTime('2023-06-15 09:45:30')),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_max' => '2023-06-15'],
        ];

        yield 'updatedBefore immutable' => [
            'request' => (new PropertiesListRequest())->updatedBefore(new \DateTimeImmutable('2023-03-10')),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_max' => '2023-03-10'],
        ];

        yield 'updatedBefore with timezone at midnight' => [
            'request' => (new PropertiesListRequest())->updatedBefore(new \DateTime('2023-03-10 00:00:00', new \DateTimeZone('America/New_York'))),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_max' => '2023-03-10'],
        ];

        yield 'updatedBefore with UTC timezone at midnight' => [
            'request' => (new PropertiesListRequest())->updatedBefore(new \DateTime('2023-03-10 00:00:00', new \DateTimeZone('UTC'))),
            'expected' => ['per_page' => 100, 'page' => 0, 'update_date_max' => '2023-03-10'],
        ];
    }

    /**
     * Data provider for mixed configuration test cases.
     */
    public static function propertiesListProvider(): iterable
    {
        yield 'custom perPage and page' => [
            'request' => (new PropertiesListRequest())->perPage(25)->page(2),
            'expected' => ['per_page' => 25, 'page' => 2],
        ];

        yield 'perPage with language' => [
            'request' => (new PropertiesListRequest())->perPage(50)->lang(['fr', 'en']),
            'expected' => ['per_page' => 50, 'page' => 0, 'lang' => 'fr|en'],
        ];

        yield 'page with language' => [
            'request' => (new PropertiesListRequest())->page(3)->lang(['de']),
            'expected' => ['per_page' => 100, 'page' => 3, 'lang' => 'de'],
        ];

        yield 'date range only' => [
            'request' => (new PropertiesListRequest())
                ->updatedAfter(new \DateTime('2023-01-01'))
                ->updatedBefore(new \DateTime('2023-12-31')),
            'expected' => [
                'per_page' => 100,
                'page' => 0,
                'update_date_min' => '2023-01-01',
                'update_date_max' => '2023-12-31',
            ],
        ];

        yield 'language with date range' => [
            'request' => (new PropertiesListRequest())
                ->lang(['fr', 'en'])
                ->updatedAfter(new \DateTime('2023-06-01'))
                ->updatedBefore(new \DateTime('2023-06-30')),
            'expected' => [
                'per_page' => 100,
                'page' => 0,
                'lang' => 'fr|en',
                'update_date_min' => '2023-06-01',
                'update_date_max' => '2023-06-30',
            ],
        ];

        yield 'all options combined' => [
            'request' => (new PropertiesListRequest())
                ->perPage(75)
                ->page(5)
                ->lang(['fr', 'en', 'it'])
                ->updatedAfter(new \DateTime('2023-03-15'))
                ->updatedBefore(new \DateTime('2023-09-30')),
            'expected' => [
                'per_page' => 75,
                'page' => 5,
                'lang' => 'fr|en|it',
                'update_date_min' => '2023-03-15',
                'update_date_max' => '2023-09-30',
            ],
        ];

        yield 'minimal configuration' => [
            'request' => (new PropertiesListRequest())->perPage(1)->page(0),
            'expected' => ['per_page' => 1, 'page' => 0],
        ];
    }

    public function testMandatoryProperties(): void
    {
        $request = new PropertiesListRequest();
        $result = $request->toArray();

        self::assertIsArray($result);
        self::assertArrayHasKey('per_page', $result);
        self::assertArrayHasKey('page', $result);
        self::assertSame(100, $result['per_page']);
        self::assertSame(0, $result['page']);
    }
}
