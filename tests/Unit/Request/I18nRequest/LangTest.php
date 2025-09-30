<?php

namespace Antistatique\Realforce\Tests\Unit\Request\I18nRequest;

use Antistatique\Realforce\Request\I18nRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(I18nRequest::class, 'lang')]
#[CoversClass(I18nRequest::class)]
final class LangTest extends TestCase
{
    /**
     * Test lang method transforms array to pipe-separated string.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('langProvider')]
    public function testLang(array $languages, string $expected): void
    {
        $request = new I18nRequest();
        $result = $request->lang($languages);

        // Test fluent interface.
        self::assertInstanceOf(I18nRequest::class, $result);
        self::assertSame($request, $result);

        // Test transformation by checking toArray output.
        $arrayResult = $request->toArray();
        self::assertArrayHasKey('lang', $arrayResult);
        self::assertSame($expected, $arrayResult['lang']);
    }

    /**
     * Data provider for lang test cases.
     */
    public static function langProvider(): iterable
    {
        yield 'single language' => [
            'languages' => ['fr'],
            'expected' => 'fr',
        ];

        yield 'multiple languages' => [
            'languages' => ['fr', 'en'],
            'expected' => 'fr|en',
        ];

        yield 'empty array' => [
            'languages' => [],
            'expected' => '',
        ];

        yield 'single empty string' => [
            'languages' => [''],
            'expected' => '',
        ];

        yield 'multiple empty strings' => [
            'languages' => ['', ''],
            'expected' => '|',
        ];

        yield 'mixed empty and non-empty' => [
            'languages' => ['fr', '', 'en'],
            'expected' => 'fr||en',
        ];

        yield 'duplicate languages' => [
            'languages' => ['fr', 'en', 'fr', 'de'],
            'expected' => 'fr|en|fr|de',
        ];

        yield 'case sensitive languages' => [
            'languages' => ['FR', 'en', 'IT'],
            'expected' => 'FR|en|IT',
        ];

        yield 'languages with special characters' => [
            'languages' => ['fr-FR', 'en_US', 'zh-CN'],
            'expected' => 'fr-FR|en_US|zh-CN',
        ];

        yield 'numeric string languages' => [
            'languages' => ['1', '2', '3'],
            'expected' => '1|2|3',
        ];

        yield 'languages with spaces' => [
            'languages' => ['fr CH', 'en US'],
            'expected' => 'fr CH|en US',
        ];

        yield 'single character languages' => [
            'languages' => ['f', 'e', 'i', 'd'],
            'expected' => 'f|e|i|d',
        ];

        yield 'very long language codes' => [
            'languages' => ['french-canadian-quebec', 'english-american-southern'],
            'expected' => 'french-canadian-quebec|english-american-southern',
        ];
    }

    /**
     * Test multiple lang() calls overwrite previous value.
     */
    public function testCallsOverwrite(): void
    {
        $request = new I18nRequest();

        // First call
        $request->lang(['fr', 'en']);
        $firstResult = $request->toArray();
        self::assertSame('fr|en', $firstResult['lang']);

        // Second call overwrites.
        $request->lang(['de', 'it']);
        $secondResult = $request->toArray();
        self::assertSame('de|it', $secondResult['lang']);
    }
}
