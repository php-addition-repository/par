<?php

declare(strict_types=1);

namespace Par\CoreTest\Fixtures;

use Generator;
use Par\Core\Equable;

/**
 * @internal
 *
 * @template-covariant TValue of scalar
 *
 * @implements Equable<EquableScalarObject>
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
     * @param iterable<TValue> $iterable
     *
     * @return Generator<EquableScalarObject<TValue>>
     */
    public static function fromIterable(iterable $iterable): iterable
    {
        foreach ($iterable as $value) {
            yield new self($value);
        }
    }

    /**
     * @return iterable<EquableScalarObject<int>>
     */
    public static function fromIntRange(int $start, int $end): iterable
    {
        yield from static::fromIterable(range($start, $end));
    }

    /**
     * @return self<string>
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @param TValue|null $value
     */
    private function __construct(public readonly float|bool|int|string|null $value)
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
