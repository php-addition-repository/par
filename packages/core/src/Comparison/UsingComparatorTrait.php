<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

/**
 * @template TValue
 *
 * @mixin Comparator<TValue>
 */
trait UsingComparatorTrait
{
    /**
     * Returns a comparator that uses the extractor to determine the values to compare.
     *
     * @template UValue
     *
     * @param callable(UValue): TValue $extractor The extractor to use to determine the values to compare
     *
     * @return Comparator<TValue>
     */
    public function using(callable $extractor): Comparator
    {
        return new ExtractorComparator($extractor, $this);
    }
}
