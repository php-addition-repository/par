<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

use Closure;

/**
 * @template TValue
 *
 * @implements Comparator<TValue>
 */
final class ExtractorComparator implements Comparator
{
    /**
     * @use ReversibleComparatorTrait<TValue>
     */
    use ReversibleComparatorTrait;

    /**
     * @use ThenableComparatorTrait<TValue>
     */
    use ThenableComparatorTrait;

    /**
     * @use UsingComparatorTrait<TValue>
     */
    use UsingComparatorTrait;

    /**
     * @var pure-Closure(TValue): mixed
     */
    private Closure $extractor;

    /**
     * @template UValue
     *
     * @param pure-callable(TValue):UValue $extractor
     * @param Comparator<UValue> $comparator
     */
    public function __construct(callable $extractor, private readonly Comparator $comparator)
    {
        $this->extractor = $extractor(...);
    }

    /**
     * @psalm-mutation-free
     */
    public function compare(mixed $v1, mixed $v2): Order
    {
        $extractor = $this->extractor;

        return $this->comparator->compare($extractor($v1), $extractor($v2));
    }
}
