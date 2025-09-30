<?php

namespace Antistatique\Realforce\Tests\Unit\Client;

use Antistatique\Realforce\RealforceClient;
use Antistatique\Realforce\Resource\AbstractResource;
use Antistatique\Realforce\Resource\PublicProperties;
use Antistatique\Realforce\Resource\ResourceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversMethod(RealforceClient::class, '__call')]
#[CoversClass(RealforceClient::class)]
#[CoversClass(AbstractResource::class)]
final class MagicCallTest extends TestCase
{
    /**
     * The Realforce client.
     *
     * @var RealforceClient
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new RealforceClient();
    }

    /**
     * Test successful API class instantiation with existing class.
     */
    public function testMagicCallSuccessfulInstantiation(): void
    {
        $result = $this->client->publicProperties();

        self::assertInstanceOf(ResourceInterface::class, $result);
        self::assertInstanceOf(PublicProperties::class, $result);
    }

    /**
     * Test successful API class instantiation with different case.
     */
    public function testMagicCallWithDifferentCase(): void
    {
        // The method should convert 'publicproperties' to 'Publicproperties'.
        $result = $this->client->publicproperties();

        self::assertInstanceOf(ResourceInterface::class, $result);
        self::assertInstanceOf(PublicProperties::class, $result);
    }

    /**
     * Test __call with non-existent API class throws BadMethodCallException.
     */
    public function testMagicCallWithNonExistentClass(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Undefined method nonExistentMethod');

        $this->client->nonExistentMethod();
    }

    /**
     * Test __call with empty method name throws BadMethodCallException.
     */
    public function testMagicCallWithEmptyMethodName(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Undefined method ');

        // Simulate calling an empty method name.
        $this->client->__call('', []);
    }

    /**
     * Test __call with invalid characters in method name.
     */
    public function testMagicCallWithInvalidMethodName(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Undefined method invalid-method-name');

        $this->client->__call('invalid-method-name', []);
    }

    /**
     * Test that __call passes arguments correctly to the instantiated class.
     */
    public function testMagicCallPassesArgumentsCorrectly(): void
    {
        // Test with arguments (though the constructor doesn't use them,
        // this ensures the method signature works correctly).
        $result = $this->client->publicProperties('arg1', 'arg2');

        self::assertInstanceOf(PublicProperties::class, $result);
    }

    /**
     * Test that exception conversion works properly.
     */
    public function testMagicCallExceptionConversion(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Undefined method someRandomNonExistentClass');

        $this->client->someRandomNonExistentClass();
    }

    /**
     * Test multiple calls return different instances.
     */
    public function testMagicCallReturnsNewInstances(): void
    {
        $instance1 = $this->client->publicProperties();
        $instance2 = $this->client->publicProperties();

        self::assertInstanceOf(PublicProperties::class, $instance1);
        self::assertInstanceOf(PublicProperties::class, $instance2);
        self::assertNotSame($instance1, $instance2);
    }

    /**
     * Test that class_exists check works correctly for existing classes.
     */
    public function testMagicCallClassExistsCheck(): void
    {
        // This should work because PublicProperties exists.
        $result = $this->client->publicProperties();

        self::assertInstanceOf(PublicProperties::class, $result);
    }

    /**
     * Test numeric method names are handled correctly.
     */
    public function testMagicCallWithNumericMethodName(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Undefined method 123');

        $this->client->__call('123', []);
    }
}
