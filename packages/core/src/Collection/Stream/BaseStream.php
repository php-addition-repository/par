<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

use Generator;
use loophp\iterators\ClosureIteratorAggregate;
use loophp\iterators\NormalizeIterableAggregate;
use NoRewindIterator;
use Par\Core\Assert;
use Par\Core\Collection\Operation\Apply;
use Par\Core\Collection\Operation\Every;
use Par\Core\Collection\Operation\Filter;
use Par\Core\Collection\Operation\Limit;
use Par\Core\Collection\Operation\Map;
use Par\Core\Collection\Operation\Normalize;
use Par\Core\Collection\Operation\Operation;
use Par\Core\Collection\Operation\Pipe;
use Par\Core\Collection\Operation\Reduce;
use Par\Core\Collection\Operation\Skip;
use Par\Core\Collection\Operation\Sort;
use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;
use Par\Core\Optional;
use Traversable;

/**
 * TODO.
 *
 * @template TValue
 *
 * @implements Stream<TValue>
 */
abstract class BaseStream implements Stream
{
    /**
     * @var ClosureIteratorAggregate<int, TValue>
     */
    private ClosureIteratorAggregate $innerIterator;

    public static function empty(): static
    {
        return static::fromIterable([]);
    }

    public static function fromCallable(callable $callable, iterable $parameters): static
    {
        return new static(
            static fn(): Generator => yield from new NormalizeIterableAggregate(
                new ClosureIteratorAggregate($callable, $parameters)
            )
        );
    }

    public static function fromIterable(iterable $iterable): static
    {
        if ($iterable instanceof static) {
            return $iterable;
        }

        if ($iterable instanceof Generator) {
            return static::fromGenerator($iterable);
        }

        return new static(
            static fn(): iterable => new NormalizeIterableAggregate($iterable)
        );
    }

    public static function fromGenerator(Generator $generator): static
    {
        return new static(
            static fn(): Generator => yield from new NormalizeIterableAggregate(
                new NoRewindIterator($generator)
            )
        );
    }

    public static function times(int $amount, callable $callable): static
    {
        return new static(
            static function(int $amount, callable $callable): iterable {
                if ($amount < 1) {
                    return;
                }

                for ($current = 1; $current <= $amount; ++$current) {
                    yield $callable($current);
                }
            },
            [$amount, $callable]
        );
    }

    /**
     * @param callable|Operation<mixed, TValue> $callable
     * @param iterable<int, mixed> $parameters
     */
    final protected function __construct(callable|Operation $callable, iterable $parameters = [])
    {
        $this->innerIterator = new ClosureIteratorAggregate($callable, $parameters);
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    public function count(): int
    {
        return iterator_count($this);
    }

    public function getIterator(): Traversable
    {
        yield from $this->innerIterator->getIterator();
    }

    public function allMatch(callable $predicate): bool
    {
        return $this->pipe(
            new Every($predicate)
        )
                    ->current()
                    ->orElse(true);
    }

    public function anyMatch(callable $predicate): bool
    {
        return $this->pipe(
            new Every(static fn(mixed $item): bool => true !== $predicate($item)),
            new Map(static fn(bool $match): bool => !$match)
        )
                    ->current()
                    ->orElse(false);
    }

    public function map(callable $mapper): Stream
    {
        return $this->pipe(new Map($mapper));
    }

    public function filter(callable $predicate): Stream
    {
        return $this->pipe(new Filter($predicate), new Normalize());
    }

    public function forEach(callable $action): void
    {
        foreach ($this as $value) {
            $action($value);
        }
    }

    public function isEmpty(): bool
    {
        return !$this->anyMatch(static fn(): bool => true);
    }

    public function limit(int $maxSize): Stream
    {
        Assert::greaterThanEq($maxSize, 0);
        if (0 === $maxSize) {
            return self::empty();
        }

        return $this->pipe(new Limit($maxSize));
    }

    public function max(callable|Comparator|null $comparator = null): Optional
    {
        $comparator = $this->toComparator($comparator)->reversed();

        return $this->pipe(
            new Sort($comparator),
            new Limit(1)
        )->current();
    }

    public function min(callable|Comparator|null $comparator = null): Optional
    {
        $comparator = $this->toComparator($comparator);

        return $this->pipe(
            new Sort($comparator),
            new Limit(1)
        )->current();
    }

    public function noneMatch(callable $predicate): bool
    {
        return !$this->anyMatch($predicate);
    }

    public function peek(callable $action): Stream
    {
        return $this->pipe(new Apply($action));
    }

    public function skip(int $num): Stream
    {
        Assert::greaterThanEq($num, 0);
        if (0 === $num) {
            return $this;
        }

        return $this->pipe(new Skip($num), new Normalize());
    }

    public function sorted(callable|Comparator|null $comparator = null): Stream
    {
        $comparator = $this->toComparator($comparator);

        return $this->pipe(new Sort($comparator), new Normalize());
    }

    public function findFirst(): Optional
    {
        return $this->current();
    }

    public function reduce(mixed $initialValue, callable $accumulator): mixed
    {
        return $this->pipe(new Reduce($initialValue, $accumulator))
                    ->current()
                    ->orElse($initialValue);
    }

    /**
     * @template TOut
     *
     * @param callable(iterable<TValue>): iterable<TOut> ...$operations
     *
     * @return BaseStream<TOut>&static
     */
    protected function pipe(callable ...$operations): static
    {
        return match (count($operations)) {
            0 => $this,
            1 => new static($operations[0], [$this]),
            default => new static(new Pipe(...$operations), [$this])
        };
    }

    /**
     * @return Optional<TValue>
     */
    protected function current(): Optional
    {
        return Optional::fromCurrent($this->innerIterator);
    }

    /**
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator
     *
     * @return Comparator<TValue>
     */
    public function toComparator(callable|Comparator|null $comparator): Comparator
    {
        if (!$comparator instanceof Comparator) {
            $comparator = is_callable($comparator) ? Comparators::with($comparator) : Comparators::values();
        }

        return $comparator;
    }
}
