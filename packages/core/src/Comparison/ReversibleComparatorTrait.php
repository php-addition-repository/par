<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

/**
 * @template TValue
 * @mixin Comparator<TValue>
 * @psalm-immutable
 */
trait ReversibleComparatorTrait
{
    public function reversed(): Comparator
    {
        return new ReverseComparator($this);
    }
}
