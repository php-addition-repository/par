<?php

declare(strict_types=1);

namespace Par\CoreTest\Fixtures;

use Par\Core\Equable;

/**
 * @internal
 *
 * @psalm-immutable
 *
 * @implements Equable<self>
 */
final class EquableScalarObject implements Equable
{
    /**
     * @param scalar|null $value
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
