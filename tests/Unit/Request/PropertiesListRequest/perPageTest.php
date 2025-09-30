<?php

namespace Antistatique\Realforce\Tests\Unit\Request\PropertiesListRequest;

use Antistatique\Realforce\Request\PropertiesListRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(PropertiesListRequest::class, 'perPage')]
#[CoversClass(PropertiesListRequest::class)]
final class perPageTest extends TestCase
{
    public function testPerPageExceedsMaximum(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('per_page cannot exceed 100');

        (new PropertiesListRequest())->perPage(150);
    }

    /**
     * Test multiple perPage() calls overwrite previous value.
     */
    public function testCallsOverwrite(): void
    {
        $request = new PropertiesListRequest();

        // First call.
        $request->perPage(50);
        $firstResult = $request->toArray();
        self::assertSame(50, $firstResult['per_page']);

        // Second call overwrites.
        $request->perPage(60);
        $secondResult = $request->toArray();
        self::assertSame(60, $secondResult['per_page']);
    }

    /**
     * Test perPage method with method chaining.
     */
    public function testMethodChaining(): void
    {
        $request = (new PropertiesListRequest())
          ->perPage(25)
          ->lang(['fr', 'en']);

        $result = $request->toArray();

        self::assertSame('fr|en', $result['lang']);
        self::assertSame(25, $result['per_page']);
    }
}
