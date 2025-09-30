<?php

namespace Antistatique\Realforce\Tests\Unit\Request\I18nRequest;

use Antistatique\Realforce\Request\I18nRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(I18nRequest::class, 'toArray')]
#[CoversClass(I18nRequest::class)]
final class ToArrayTest extends TestCase
{
    /**
     * Test toArray with different configurations.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('languageProvider')]
    public function testToArray(I18nRequest $request, array $expected): void
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
            'request' => (new I18nRequest())->lang(['fr']),
            'expected' => ['lang' => 'fr'],
        ];

        yield 'two languages' => [
            'request' => (new I18nRequest())->lang(['fr', 'en']),
            'expected' => ['lang' => 'fr|en'],
        ];

        yield 'empty languages' => [
            'request' => (new I18nRequest())->lang(['']),
            'expected' => ['lang' => ''],
        ];
    }

    public function testMandatoryProperties(): void
    {
        $request = new I18nRequest();
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Typed property Antistatique\Realforce\Request\I18nRequest::$lang must not be accessed before initialization');
        $request->toArray();
    }
}
