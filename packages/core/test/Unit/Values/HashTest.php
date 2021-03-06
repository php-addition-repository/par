<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Values;

use Par\Core\Hashable;
use Par\Core\HashCode;
use Par\Core\Values;
use Par\CoreTest\Traits\ResourceTrait;
use PHPUnit\Framework\TestCase;
use stdClass;

final class HashTest extends TestCase
{
    use ResourceTrait;

    /**
     * @test
     */
    public function itReturnsHashOfHashable(): void
    {
        $expectedHash = microtime(true);

        $hashable = $this->createMock(Hashable::class);
        $hashable->expects(self::once())
                 ->method('hash')
                 ->with()
                 ->willReturn($expectedHash);

        self::assertEquals($expectedHash, Values::hash($hashable));
    }

    /**
     * @return array<string, array{scalar|null}>
     */
    public function provideScalarAndNullValue(): array
    {
        return [
            'null' => [null],
            'float' => [0.1],
            'int' => [-1],
            'string' => ['Hello World!'],
            'bool' => [false],
        ];
    }

    /**
     * @test
     * @dataProvider provideScalarAndNullValue
     *
     * @param scalar|null $scalarOrNullValue
     */
    public function itReturnsValueForScalarAndNullValue(bool|float|int|string|null $scalarOrNullValue): void
    {
        self::assertEquals($scalarOrNullValue, Values::hash($scalarOrNullValue));
    }

    /**
     * @return array<string, array{object|resource|closed-resource|callable|iterable|array, int}>
     */
    public function provideNonScalarOrNullValueAndHash(): array
    {
        $obj = new stdClass();

        $resource = $this->createResource();
        $closedResource = $this->createClosedResource();

        return [
            'object' => [$obj, HashCode::forObject($obj)],
            'resource' => [$resource, HashCode::forResource($resource)],
            'resource(closed)' => [$closedResource, HashCode::forResource($closedResource)],
            'array-list' => [[1, 4], 5],
            'array-map' => [[1 => 'foo', 4 => 'bar'], 198878],
            'array-max-recursion' => [[1, [1, [1, [1, [1, [1, [1, [1, [1, [1, [1, [1, []]]]]]]]]]]]], 10],
        ];
    }

    /**
     * @test
     * @dataProvider provideNonScalarOrNullValueAndHash
     *
     * @param object|resource|closed-resource|callable|iterable|array $nonScalarValue
     * @param int                                                     $expectedHashCode
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function itReturnsHashCodeForNonScalarOrNullValue($nonScalarValue, int $expectedHashCode): void
    {
        self::assertEquals($expectedHashCode, Values::hash($nonScalarValue));
    }
}
