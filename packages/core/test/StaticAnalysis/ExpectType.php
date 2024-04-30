<?php

declare(strict_types=1);

namespace Par\CoreTest\StaticAnalysis;

final class ExpectType
{
    public static function int(int $int): void
    {
    }

    public static function string(string $value): void
    {
    }

    /**
     * @template TValue
     *
     * @param class-string<TValue> $type
     * @param TValue $result
     */
    public static function instanceOf(string $type, mixed $result): void
    {
    }

    /** @param iterable<int> $ints */
    public static function allInts(iterable $ints): void
    {
    }

    /** @param iterable<float> $floats */
    public static function allFloats(iterable $floats): void
    {
    }
}
