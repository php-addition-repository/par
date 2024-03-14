<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

use Closure;

/**
 * @template TValue
 *
 * @implements  Comparator<TValue>
 */
final class CallableComparator implements Comparator
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
     * @var pure-Closure(TValue, TValue):(Order|int<-1,1>)
     */
    private readonly Closure $comparator;

    /**
     * @param pure-callable(TValue, TValue):(Order|int<-1,1>) $comparator
     */
    public function __construct(callable $comparator)
    {
        $this->comparator = $comparator(...);
    }

    /**
     * @psalm-mutation-free
     */
    public function compare(mixed $v1, mixed $v2): Order
    {
        $comparator = $this->comparator;

        $order = $comparator($v1, $v2);
        if (is_int($order)) {
            return Order::from($order);
        }

        return $order;
    }
}
