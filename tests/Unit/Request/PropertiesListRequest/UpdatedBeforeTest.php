<?php

namespace Antistatique\Realforce\Tests\Unit\Request\PropertiesListRequest;

use Antistatique\Realforce\Request\PropertiesListRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PropertiesListRequest::class, 'updatedBefore')]
#[CoversClass(PropertiesListRequest::class)]
final class UpdatedBeforeTest extends TestCase
{
    /**
     * Test updatedBefore method sets update_date_max in toArray output.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('updatedBeforeProvider')]
    public function testUpdatedBefore(\DateTimeInterface $date, string $expected): void
    {
        $request = new PropertiesListRequest();
        $result = $request->updatedBefore($date);

        // Test fluent interface.
        self::assertInstanceOf(PropertiesListRequest::class, $result);
        self::assertSame($request, $result);

        // Test transformation by checking toArray output.
        $arrayResult = $request->toArray();
        self::assertArrayHasKey('update_date_max', $arrayResult);
        self::assertSame($expected, $arrayResult['update_date_max']);
    }

    /**
     * Data provider for updatedBefore test cases.
     */
    public static function updatedBeforeProvider(): iterable
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
     * Test that update_date_max is absent when updatedBefore is not called.
     */
    public function testNotSetByDefault(): void
    {
        $arrayResult = (new PropertiesListRequest())->toArray();

        self::assertArrayNotHasKey('update_date_max', $arrayResult);
    }

    /**
     * Test multiple updatedBefore() calls overwrite previous value.
     */
    public function testCallsOverwrite(): void
    {
        $request = new PropertiesListRequest();

        // First call.
        $request->updatedBefore(new \DateTimeImmutable('2024-06-30'));
        $firstResult = $request->toArray();
        self::assertSame('2024-06-30', $firstResult['update_date_max']);

        // Second call overwrites.
        $request->updatedBefore(new \DateTimeImmutable('2024-12-31'));
        $secondResult = $request->toArray();
        self::assertSame('2024-12-31', $secondResult['update_date_max']);
    }

    /**
     * Test updatedBefore method with method chaining.
     */
    public function testMethodChaining(): void
    {
        $request = (new PropertiesListRequest())
          ->updatedBefore(new \DateTimeImmutable('2024-12-31'))
          ->page(2);

        $result = $request->toArray();

        self::assertSame('2024-12-31', $result['update_date_max']);
        self::assertSame(2, $result['page']);
    }
}
