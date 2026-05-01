<?php

namespace Antistatique\Realforce\Tests\Unit\Request\PropertiesListRequest;

use Antistatique\Realforce\Request\PropertiesListRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PropertiesListRequest::class, 'updatedAfter')]
#[CoversClass(PropertiesListRequest::class)]
final class UpdatedAfterTest extends TestCase
{
    /**
     * Test updatedAfter method sets update_date_min in toArray output.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('updatedAfterProvider')]
    public function testUpdatedAfter(\DateTimeInterface $date, string $expected): void
    {
        $request = new PropertiesListRequest();
        $result = $request->updatedAfter($date);

        // Test fluent interface.
        self::assertInstanceOf(PropertiesListRequest::class, $result);
        self::assertSame($request, $result);

        // Test transformation by checking toArray output.
        $arrayResult = $request->toArray();
        self::assertArrayHasKey('update_date_min', $arrayResult);
        self::assertSame($expected, $arrayResult['update_date_min']);
    }

    /**
     * Data provider for updatedAfter test cases.
     */
    public static function updatedAfterProvider(): iterable
    {
        yield 'DateTime object' => [
            'date' => new \DateTime('2024-01-15'),
            'expected' => '2024-01-15',
        ];

        yield 'DateTimeImmutable object' => [
            'date' => new \DateTimeImmutable('2024-06-30'),
            'expected' => '2024-06-30',
        ];

        yield 'start of year' => [
            'date' => new \DateTimeImmutable('2023-01-01'),
            'expected' => '2023-01-01',
        ];

        yield 'end of year' => [
            'date' => new \DateTimeImmutable('2023-12-31'),
            'expected' => '2023-12-31',
        ];

        yield 'time component is ignored in format' => [
            'date' => new \DateTime('2024-03-20 15:45:00'),
            'expected' => '2024-03-20',
        ];
    }

    /**
     * Test that update_date_min is absent when updatedAfter is not called.
     */
    public function testNotSetByDefault(): void
    {
        $arrayResult = (new PropertiesListRequest())->toArray();

        self::assertArrayNotHasKey('update_date_min', $arrayResult);
    }

    /**
     * Test multiple updatedAfter() calls overwrite previous value.
     */
    public function testCallsOverwrite(): void
    {
        $request = new PropertiesListRequest();

        // First call.
        $request->updatedAfter(new \DateTimeImmutable('2024-01-01'));
        $firstResult = $request->toArray();
        self::assertSame('2024-01-01', $firstResult['update_date_min']);

        // Second call overwrites.
        $request->updatedAfter(new \DateTimeImmutable('2024-06-01'));
        $secondResult = $request->toArray();
        self::assertSame('2024-06-01', $secondResult['update_date_min']);
    }

    /**
     * Test updatedAfter method with method chaining.
     */
    public function testMethodChaining(): void
    {
        $request = (new PropertiesListRequest())
          ->updatedAfter(new \DateTimeImmutable('2024-01-01'))
          ->page(2);

        $result = $request->toArray();

        self::assertSame('2024-01-01', $result['update_date_min']);
        self::assertSame(2, $result['page']);
    }
}
