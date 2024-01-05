<?php

declare(strict_types=1);

namespace Par\CoreTest\Fixtures;

use Par\Core\Comparison\Comparable;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\Order;

/**
 * @template TValue of int|string
 * @internal
 * @psalm-immutable
 * @implements Comparable<self>
 */
final class ComparableScalarObject implements Comparable
{
    public function __construct(public readonly int|string $value)
    {
    }

    public function compare(mixed $other): Order
    {
        if ($other instanceof self && gettype($this->value) === gettype($other->value)) {
            return Order::from($this->value <=> $other->value);
        }

        throw IncomparableException::withIncompatibleTypes(
            $this,
            $other,
            sprintf('%s<%s>', self::class, gettype($this->value))
        );
    }
}
