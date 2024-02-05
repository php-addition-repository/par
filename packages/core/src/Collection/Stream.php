<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Countable;
use Generator;
use IteratorAggregate;
use loophp\collection\Collection as CollectionFactory;
use loophp\collection\Contract\Collection as CollectionInterface;
use loophp\collection\Contract\Operation\Sortable;
use loophp\collection\Operation\MatchOne;
use Par\Core\Assert;
use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;
use Par\Core\Exception\InvalidArgumentException;
use Par\Core\Optional;
use Traversable;

/**
 * A sequence of elements supporting sequential operations.
 *
 * To perform a computation, stream operations are composed into a stream pipeline. A stream pipeline consists of a
 * source (which might be an array, a collection, a generator function, etc), zero or more intermediate
 * operations (which transform a stream into another stream, such as `filter(callable $predicate)`), and a terminal
 * operation (which produces a result or side-effect, such as `count()` or `forEach(callable $action)`). Streams are
 * lazy; computation on the source data is only performed when the terminal operation is initiated, and source elements
 * are consumed only as needed.
 *
 * @immutable
 *
 * @template TValue
 *
 * @implements IteratorAggregate<int, TValue>
 */
final class Stream implements IteratorAggregate, Countable
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
     * @return self<mixed>
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
     * @param float $step step size of each item in range stream, must be zero or more
     *
     * @return self<float>
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
     * @param iterable<int, mixed> $parameters The parameters to execute the callable with
     *
     * @return self<UValue>
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
     * @param Generator<UValue> $generator The generator to use
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
     * @param iterable<UValue> $iterable the iterable to use
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
     * @param int $start Start integer of range
     * @param int $end Maximum integer in range, must be greater that or equal to start
     * @param int<0, max> $step step size of each item in range stream, must be zero or more
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
     * @param int $amount The amount of times to execute the callback
     * @param callable(int):UValue|null $callable The callable to invoke
     *
     * @return ($callable is null ? Stream<int> : Stream<UValue>)
     */
    public static function times(int $amount = 0, ?callable $callable = null): self
    {
        return new self(CollectionFactory::times($amount, $callable));
    }

    /**
     * Returns whether all elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If this stream is
     * empty, `true` is returned.
     *
     * @param callable(TValue): bool $predicate A predicate to apply to elements of this stream
     */
    public function allMatch(callable $predicate): bool
    {
        return $this->innerCollection->every($predicate);
    }

    /**
     * Returns whether any elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If the stream is
     * empty, `false` is returned.
     *
     * @param callable(TValue): bool $predicate A predicate to apply to elements of this stream
     */
    public function anyMatch(callable $predicate): bool
    {
        $matcher = static fn(): bool => true;

        return (new MatchOne())()($matcher)($predicate)($this->innerCollection)->current();
    }

    /**
     * Returns the count of elements in this stream.
     */
    public function count(): int
    {
        return iterator_count($this->innerCollection);
    }

    /**
     * Returns a stream consisting of the elements of this stream that match the given predicate.
     *
     * This is an intermediate operation.
     *
     * @param callable(TValue): bool $predicate a non-interfering predicate to apply to each element to
     *                                          determine if it should be included
     *
     * @return static<TValue> The new stream
     */
    public function filter(callable $predicate): Stream
    {
        return new self($this->innerCollection->filter($predicate));
    }

    /**
     * Performs an action for each element of this stream.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue): void $action a non-interfering action to perform on the elements
     */
    public function forEach(callable $action): void
    {
        $this->peek($action)->innerCollection->squash();
    }

    /**
     * @return Traversable<int, TValue>
     */
    public function getIterator(): Traversable
    {
        return $this->innerCollection->getIterator();
    }

    /**
     * Returns `true` if this collection contains no elements.
     *
     * The implementation behind this method might be more optimized then `count($this) === 0` since counting the
     * collection could require iterating over all elements.
     *
     * @return bool `true` if this collection contains no elements
     */
    public function isEmpty(): bool
    {
        return $this->anyMatch(static fn(): bool => true);
    }

    /**
     * Returns a stream consisting of the elements of this stream, truncated to be no longer than maxSize in length.
     *
     * This is an intermediate operation.
     *
     * @param int<0, max> $maxSize the number of elements the stream should be limited to
     *
     * @return static<TValue> The new stream
     *
     * @throws InvalidArgumentException if maxSize is negative
     */
    public function limit(int $maxSize): Stream
    {
        Assert::greaterThanEq($maxSize, 0);
        if (0 === $maxSize) {
            return self::empty();
        }

        return new self($this->innerCollection->limit($maxSize)->normalize());
    }

    /**
     * Returns a stream consisting of the results of applying the given function to the elements of this stream.
     *
     * This is an intermediate operation.
     *
     * @template UValue
     *
     * @param callable(TValue): UValue $mapper a function to apply to each element
     *
     * @return static<UValue> The new stream
     */
    public function map(callable $mapper): Stream
    {
        return new self($this->innerCollection->map($mapper));
    }

    /**
     * Returns the maximum element of this stream according to the provided comparator.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a comparator to compare
     *                                                                                elements of this stream
     *
     * @return Optional<TValue> an Optional describing the maximum element of this stream, or an empty Optional if the
     *                          stream is empty
     */
    public function max(callable|Comparator|null $comparator = null): Optional
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
     * Returns the minimum element of this stream according to the provided comparator.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a comparator to compare
     *                                                                                *     elements of this stream
     *
     * @return Optional<TValue> an Optional describing the maximum element of this stream, or an empty Optional if the
     *                          stream is empty
     */
    public function min(callable|Comparator|null $comparator = null): Optional
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
     * Returns whether no elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If the stream is
     * empty then true is returned and the predicate is not evaluated.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue): bool $predicate a non-interfering predicate to apply to elements of this stream
     *
     * @return bool `true` if either no elements of the stream match the provided predicate or the stream is empty,
     *              otherwise `false`
     */
    public function noneMatch(callable $predicate): bool
    {
        return !$this->anyMatch($predicate);
    }

    /**
     * Returns a stream consisting of the elements of this stream, additionally performing the provided action on each
     * element as elements are consumed from the resulting stream.
     *
     * This is an intermediate operation.
     *
     * @param callable(TValue): void $action a non-interfering action to perform on the elements as they are
     *                                       consumed from the stream
     *
     * @return Stream<TValue> The new stream
     */
    public function peek(callable $action): self
    {
        return new self($this->innerCollection->apply(static function(mixed $value) use ($action): bool {
            $action($value);

            return true;
        }));
    }

    /**
     * Returns a stream consisting of the remaining elements of this stream after discarding the first n elements of
     * the stream.
     *
     * If this stream contains fewer than n elements then an empty stream will be returned.
     *
     * This is an intermediate operation.
     *
     * @param int<0, max> $num the number of leading elements to skip
     *
     * @return static<TValue> The new stream
     *
     * @throws InvalidArgumentException if num is negative
     */
    public function skip(int $num): Stream
    {
        Assert::greaterThanEq($num, 0);
        if (0 === $num) {
            return $this;
        }

        return new self($this->innerCollection->drop($num)->normalize());
    }

    /**
     * Returns a stream consisting of the elements of this stream, sorted according to the provided comparator.
     *
     * This is an intermediate operation.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a non-interfering comparator
     *                                                                                to be used to compare stream elements
     *
     * @return static<TValue> The new stream
     */
    public function sorted(callable|Comparator|null $comparator = null): self
    {
        return new self(
            $this->innerCollection->sort(
                Sortable::BY_VALUES,
                $this->toInnerComparator($comparator)
            )->normalize()
        );
    }

    /**
     * Transform this stream to an array.
     *
     * This is a terminal operation.
     *
     * @return TValue[]
     */
    public function toArray(): array
    {
        return $this->innerCollection->all();
    }

    /**
     * Transforms a comparator to a callable that can be used by the internal collection.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator The comparator to transform
     *
     * @return callable(TValue, TValue): int<-1, 1>
     */
    private function toInnerComparator(callable|Comparator|null $comparator = null): callable
    {
        if (!$comparator instanceof Comparator) {
            $comparator = is_callable($comparator) ? Comparators::with($comparator) : Comparators::values();
        }

        return static fn(mixed $a, mixed $b): int => $comparator->compare($a, $b)->value;
    }
}
