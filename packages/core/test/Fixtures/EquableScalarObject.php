<?php

declare(strict_types=1);

namespace Par\CoreTest\Fixtures;

use Generator;
use Par\Core\Equable;

/**
 * @internal
 *
 * @template-covariant TValue of float|bool|int|string
 *
 * @implements Equable<EquableScalarObject<float|bool|int|string>>
 */
final class EquableScalarObject implements Equable
{
    /**
     * @return self<int>
     */
    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    /**
     * @return Generator<self<int>>
     */
    public static function fromIntRange(int $start, int $end): Generator
    {
        yield from static::fromIterable(range($start, $end));
    }

    /**
     * @param iterable<TValue> $iterable
     *
     * @return Generator<self<TValue>>
     */
    public static function fromIterable(iterable $iterable): Generator
    {
        foreach ($iterable as $value) {
            yield new self($value);
        }
    }

    /**
     * @return self<string>
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @return Generator<self<string>>
     */
    public static function fromStringRange(string $start, string $end): Generator
    {
        yield from static::fromIterable(range($start, $end));
    }

    private function __construct(public readonly float|bool|int|string $value)
    {
    }

    public function equals(mixed $other): bool
    {
        if ($other instanceof self) {
            return $other->value === $this->value;
        }

        return false;
    }
}
