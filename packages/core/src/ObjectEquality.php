<?php

declare(strict_types=1);

namespace Par\Core;

/**
 * An object implementing this interface can determine if it equals any other value.
 */
interface ObjectEquality
{
    /**
     * Determines if object should be considered equal to other value.
     *
     * In most cases the method evaluates to true if the other value has the type and internal value(s).
     *
     * @param mixed $otherValue The other value with which to compare
     *
     * @return bool True if this object should be considered equal to other value
     * @example "packages/core/test/Fixtures/ScalarValueObject.php" Implementation example
     *
     * @psalm-mutation-free
     * @psalm-assert-if-true static $otherValue
     */
    public function equals(mixed $otherValue): bool;
}