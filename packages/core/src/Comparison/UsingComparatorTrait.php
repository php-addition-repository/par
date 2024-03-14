<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

/**
 * @template TValue
 *
 * @mixin Comparator<TValue>
 *
 * @psalm-immutable
 */
trait UsingComparatorTrait
{
    /**
     * Returns a comparator that uses the extractor to determine the values to compare.
     *
     * @template UValue
     *
     * @param pure-callable(UValue): TValue $extractor The extractor to use to determine the values to compare
     *
     * @return Comparator<TValue>
     *
     * @psalm-mutation-free
     */
    public function using(callable $extractor): Comparator
    {
        return new ExtractorComparator($extractor, $this);
    }
}
