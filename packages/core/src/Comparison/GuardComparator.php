<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

use Closure;
use Par\Core\Comparison\Exception\IncomparableException;

/**
 * This comparator makes sure that both values pass provided predicate.
 *
 * It will throw a `Par\Core\Comparator\IncomparableException` when the predicate returns` false` for either value in
 * the comparison.
 *
 * @template TValue
 * @implements Comparator<TValue>
 */
final class GuardComparator implements Comparator
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

    /** @var pure-Closure(TValue): bool */
    private Closure $test;

    /**
     * @param Comparator<TValue>          $guardedComparator The comparator that is guarded
     * @param pure-callable(TValue): bool $predicate The predicate to use
     * @param string                      $additionalInfo Optional additional info to add to the thrown exception
     *     message.
     */
    public function __construct(
        private readonly Comparator $guardedComparator,
        callable $predicate,
        private readonly string $additionalInfo = ''
    ) {
        $this->test = $predicate(...);
    }

    /**
     * @inheritDoc
     * @psalm-external-mutation-free
     */
    public function compare(mixed $v1, mixed $v2): Order
    {
        $test = $this->test;
        if (!$test($v1) || !$test($v2)) {
            throw IncomparableException::withIncompatibleTypes($v1, $v2, $this->additionalInfo);
        }

        return $this->guardedComparator->compare($v1, $v2);
    }
}
