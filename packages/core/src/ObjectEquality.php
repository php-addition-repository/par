<?php

declare(strict_types=1);

namespace Par\Core;

/**
 * An object implementing this interface can determine if it equals any other value.
 *
 * Strict comparison (`$a === $b`) does not work on different instances of objects that represent the same value.
 * Regular comparison (`$a == $b`) is possible, but you need to remember to use it on object comparison, but not on
 * other value types which is confusing. By implementing this interface on the objects that require comparison you can
 * use `$a->equals($b)` and you have all the control.
 */
interface ObjectEquality
{
    /**
     * Determines if object should be considered equal to other value.
     *
     * In most cases the method evaluates to true if the other value has the same type and internal value(s) with an
     * implementation like the following.
     * ```php
     * public function equals(mixed $other): bool
     * {
     *    if ($other instanceof self) {
     *        return $other->value === $this->value;
     *    }
     *
     *    return false;
     * }
     * ```
     *
     * @param mixed $otherValue The other value with which to compare
     *
     * @return bool True if this object should be considered equal to other value
     * @example packages/core/test/Fixtures/ScalarValueObject.php 15 7 Implementation example
     *
     * @psalm-mutation-free
     * @psalm-assert-if-true static $otherValue
     */
    public function equals(mixed $otherValue): bool;
}
