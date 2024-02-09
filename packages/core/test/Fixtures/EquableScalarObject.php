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
     * @param iterable<TValue> $values
     *
     * @return Generator<EquableScalarObject<TValue>>
     */
    public static function generateList(iterable $values): iterable
    {
        foreach ($values as $value) {
            yield new self($value);
        }
    }

    /**
     * @param TValue|null $value
     */
    public function __construct(public readonly float|bool|int|string|null $value)
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
