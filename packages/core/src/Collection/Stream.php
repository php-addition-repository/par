<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Generator;
use loophp\collection\Collection as CollectionFactory;
use loophp\collection\Contract\Collection as CollectionInterface;
use loophp\collection\Contract\Operation\Sortable;
use loophp\collection\Operation\MatchOne;
use Par\Core\Assert;
use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;
use Par\Core\Optional;
use Traversable;

/**
 * @immutable
 * @template TValue
 * @implements Collection<int, TValue>
 */
final class Stream implements Collection
{
    /**
     * @param CollectionInterface<int, TValue> $innerCollection
     */
    private function __construct(private readonly CollectionInterface $innerCollection)
    {
    }

    /**
     * Create an empty stream.
     *
     * @template UValue
     *
     * @return Stream<UValue>
     */
    public static function empty(): self
    {
        return new self(CollectionFactory::empty());
    }

    /**
     * Create stream of floats.
     *
     * @param float $start Start float of range
     * @param float $end Maximum float in range, must be greater that or equal to start
     * @param float $step Step size of each item in range stream, must be zero or more.
     *
     * @return Stream<float>
     */
    public static function floatRange(float $start = 0.0, float $end = INF, float $step = 1.0): self
    {
        Assert::greaterThanEq($end, $start, 'Range end must be greater than or equal to range start');
        Assert::greaterThanEq($step, 0, 'Range step must be zero or more');

        return new self(
            CollectionFactory::range(
                $start,
                $end + 1,
                $step
            )
        );
    }

    /**
     * Create a stream from a callable.
     *
     * @template UValue
     *
     * @param callable(mixed ...$parameters): iterable<UValue> $callable The callable to execute
     * @param iterable<int, mixed>                             $parameters The parameters to execute the callable with
     *
     * @return Stream<UValue>
     */
    public static function fromCallable(callable $callable, iterable $parameters): self
    {
        return new self(CollectionFactory::fromCallable($callable, $parameters));
    }

    /**
     * Create a stream from a Generator.
     *
     * __WARNING:__ The difference between this factory and `fromIterable` is that the generator is decorated with
     * a caching iterator. Generators are not rewindable by design and using `fromGenerator` automatically adds the
     * caching layer for you.
     *
     * @template UValue
     *
     * @param Generator<UValue> $generator The generator to use.
     *
     * @return Stream<UValue>
     */
    public static function fromGenerator(Generator $generator): self
    {
        return new self(CollectionFactory::fromGenerator($generator));
    }

    /**
     * Create a stream from any iterable.
     *
     * __WARNING:__ When instantiating from a PHP Generator, the stream will inherit its behaviour: it will
     * only be iterable a single time, and an exception will be thrown if multiple operations which attempt to
     * re-iterate are applied, for example `count()`. To circumvent this internal PHP limitation, use
     * `Stream::fromGenerator()` or even better `Stream::fromCallable()` which requires the generating callable not yet
     * initialized.
     *
     * @template UValue
     *
     * @param iterable<UValue>|Stream<UValue> $iterable The iterable to use.
     *
     * @return Stream<UValue>
     */
    public static function fromIterable(iterable $iterable): self
    {
        if ($iterable instanceof self) {
            return $iterable;
        }

        return new self(CollectionFactory::fromIterable($iterable));
    }

    /**
     * Create integer stream.
     *
     * @param int         $start Start integer of range
     * @param int         $end Maximum integer in range, must be greater that or equal to start
     * @param int<0, max> $step Step size of each item in range stream, must be zero or more.
     *
     * @return Stream<int>
     */
    public static function intRange(int $start = 0, int $end = PHP_INT_MAX, int $step = 1): self
    {
        Assert::greaterThanEq($end, $start, 'Range end must be greater than or equal to range start');
        Assert::greaterThanEq($step, 0, 'Range step must be zero or more');

        return self::floatRange(floatval($start), floatval($end), floatval($step))
            ->map(
                static fn(float $val): int => intval($val)
            );
    }

    /**
     * Create a stream by invoking the callback a given amount of times.
     *
     * If no callback is provided, then it will create a simple list of incremented integers.
     *
     * @template UValue
     *
     * @param int                       $amount The amount of times to execute the callback
     * @param callable(int):UValue|null $callable The callable to invoke
     *
     * @psalm-return ($callable is null ? Stream<int> : Stream<UValue>)
     */
    public static function times(int $amount = 0, callable $callable = null): self
    {
        return new self(CollectionFactory::times($amount, $callable));
    }

    /**
     * Returns whether all elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If this stream is
     * empty, `true` is returned.
     *
     * @param pure-callable(TValue): bool $predicate A predicate to apply to elements of this stream
     *
     * @return bool
     */
    public function allMatch(callable $predicate): bool
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        return $this->innerCollection->every($predicate);
    }

    /**
     * Returns whether any elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If the stream is
     * empty, `false` is returned.
     *
     * @param pure-callable(TValue): bool $predicate A predicate to apply to elements of this stream
     *
     * @return bool
     */
    public function anyMatch(callable $predicate): bool
    {
        $matcher = static fn(): bool => true;

        /** @psalm-suppress PossiblyInvalidArgument */
        return (new MatchOne())()($matcher)($predicate)($this->innerCollection)->current();
    }

    /**
     * Returns the count of elements in this stream.
     *
     * @return int
     */
    public function count(): int
    {
        return iterator_count($this->innerCollection);
    }

    /**
     * TODO
     *
     * @param callable(TValue): bool $predicate TODO
     *
     * @return static<TValue>
     */
    public function filter(callable $predicate): Stream
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        return new self($this->innerCollection->filter($predicate));
    }

    /**
     * TODO
     *
     * @param callable(TValue): void $action TODO
     *
     * @return void
     */
    public function forEach(callable $action): void
    {
        $this->peek($action)->innerCollection->squash();
    }

    /**
     * {@inheritDoc}
     * @return Traversable<int, TValue>
     */
    public function getIterator(): Traversable
    {
        return $this->innerCollection->getIterator();
    }

    /**
     * TODO
     *
     * @param int<0, max> $maxSize TODO
     *
     * @return static<TValue>
     */
    public function limit(int $maxSize): Stream
    {
        Assert::greaterThanEq($maxSize, 0);
        if ($maxSize === 0) {
            return self::empty();
        }

        return new self($this->innerCollection->limit($maxSize)->normalize());
    }

    /**
     * TODO
     * @template UValue
     *
     * @param callable(TValue): UValue $mapper TODO
     *
     * @return static<UValue>
     */
    public function map(callable $mapper): Stream
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        return new self($this->innerCollection->map($mapper));
    }

    /**
     * TODO
     *
     * @param pure-callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator TODO
     *
     * @return Optional<TValue>
     */
    public function max(callable|Comparator $comparator = null): Optional
    {
        return Optional::fromCurrent(
            $this->innerCollection->sort(
                Sortable::BY_VALUES,
                $this->toInnerComparator($comparator)
            )
                ->reverse()
                ->limit(1)
                ->getIterator()
        );
    }

    /**
     * TODO
     *
     * @param pure-callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator TODO
     *
     * @return Optional<TValue>
     */
    public function min(callable|Comparator $comparator = null): Optional
    {
        return Optional::fromCurrent(
            $this->innerCollection->sort(
                Sortable::BY_VALUES,
                $this->toInnerComparator($comparator)
            )
                ->limit(1)
                ->getIterator()
        );
    }

    /**
     * TODO
     *
     * @param callable(TValue): bool $predicate TODO
     *
     * @return bool
     */
    public function noneMatch(callable $predicate): bool
    {
        return !$this->anyMatch($predicate);
    }

    /**
     * TODO
     *
     * @param callable(TValue): void $action TODO
     *
     * @return Stream<TValue>
     */
    public function peek(callable $action): self
    {
        return new self($this->innerCollection->apply(static function (mixed $value) use ($action): bool {
            $action($value);

            return true;
        }));
    }

    /**
     * TODO
     *
     * @param int<0, max> $num TODO
     *
     * @return static<TValue>
     */
    public function skip(int $num): Stream
    {
        Assert::greaterThanEq($num, 0);
        if ($num === 0) {
            return $this;
        }

        return new self($this->innerCollection->drop($num)->normalize());
    }

    /**
     * TODO
     *
     * @param pure-callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator TODO
     *
     * @return static<TValue>
     */
    public function sorted(callable|Comparator $comparator = null): self
    {
        return new self(
            $this->innerCollection->sort(
                Sortable::BY_VALUES,
                $this->toInnerComparator($comparator)
            )->normalize()
        );
    }

    /**
     * TODO
     *
     * @param pure-callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator TODO
     *
     * @return callable(TValue, TValue): int<-1, 1>
     */
    private function toInnerComparator(callable|Comparator $comparator = null): callable
    {
        if (!$comparator instanceof Comparator) {
            $comparator = is_callable($comparator) ? Comparators::with($comparator) : Comparators::values();
        }

        return static fn(mixed $a, mixed $b): int => $comparator->compare($a, $b)->value;
    }
}
