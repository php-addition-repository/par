<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

use Countable;
use Generator;
use IteratorAggregate;
use Par\Core\Comparison\Comparator;
use Par\Core\Exception\InvalidArgumentException;
use Par\Core\Optional;
use Traversable;

/**
 * @template TValue
 *
 * @extends IteratorAggregate<int, TValue>
 */
interface Stream extends IteratorAggregate, Countable
{
    /**
     * Create an empty stream.
     *
     * @return Stream<mixed>&static
     */
    public static function empty(): static;

    /**
     * Create a stream from a callable.
     *
     * @param callable(mixed ...$parameters): iterable<TValue> $callable The callable to execute
     * @param iterable<int, mixed> $parameters The parameters to execute the callable with
     *
     * @return Stream<TValue>&static
     */
    public static function fromCallable(callable $callable, iterable $parameters): static;

    /**
     * Create a stream from a Generator.
     *
     * __WARNING:__ The difference between this factory and `fromIterable` is that the generator is decorated with
     * a caching iterator. Generators are not rewindable by design and using `fromGenerator` automatically adds the
     * caching layer for you.
     *
     * @param Generator<TValue> $generator The generator to use
     *
     * @return Stream<TValue>&static
     */
    public static function fromGenerator(Generator $generator): static;

    /**
     * Create a stream from any iterable.
     *
     * __WARNING:__ When instantiating from a PHP Generator, the stream will inherit its behaviour: it will
     * only be iterable a single time, and an exception will be thrown if multiple operations which attempt to
     * re-iterate are applied, for example `count()`. To circumvent this internal PHP limitation, use
     * `Stream::fromGenerator()` or even better `Stream::fromCallable()` which requires the generating callable not yet
     * initialized.
     *
     * @param iterable<TValue> $iterable the iterable to use
     *
     * @return Stream<TValue>&static
     */
    public static function fromIterable(iterable $iterable): static;

    /**
     * Create a stream by invoking the callback a given amount of times.
     *
     * @param int<0, max> $amount The amount of times to execute the callback
     * @param callable(int):TValue $callable The callable to invoke
     *
     * @return Stream<TValue>&static
     */
    public static function times(int $amount, callable $callable): static;

    /**
     * Returns whether all elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If this stream is
     * empty, `true` is returned.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue): bool $predicate A predicate to apply to elements of this stream
     */
    public function allMatch(callable $predicate): bool;

    /**
     * Returns whether any elements of this stream match the provided predicate.
     *
     * May not evaluate the predicate on all elements if not necessary for determining the result. If the stream is
     * empty, `false` is returned.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue): bool $predicate A predicate to apply to elements of this stream
     */
    public function anyMatch(callable $predicate): bool;

    /**
     * Returns the count of elements in this stream.
     *
     * This is a terminal operation.
     */
    public function count(): int;

    /**
     * Returns a stream consisting of the elements of this stream that match the given predicate.
     *
     * This is an intermediate operation.
     *
     * @param callable(TValue): bool $predicate a non-interfering predicate to apply to each element to determine if it should be included
     *
     * @return Stream<TValue> The new stream
     */
    public function filter(callable $predicate): Stream;

    /**
     * Performs an action for each element of this stream.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue): void $action a non-interfering action to perform on the elements
     */
    public function forEach(callable $action): void;

    /**
     * @return Traversable<int, TValue>
     */
    public function getIterator(): Traversable;

    /**
     * Returns `true` if this collection contains no elements.
     *
     * The implementation behind this method might be more optimized then `count($this) === 0` since counting the
     * collection could require iterating over all elements.
     *
     * This is a terminal operation.
     *
     * @return bool `true` if this collection contains no elements
     */
    public function isEmpty(): bool;

    /**
     * Returns a stream consisting of the elements of this stream, truncated to be no longer than maxSize in length.
     *
     * This is an intermediate operation.
     *
     * @param int<0, max> $maxSize the number of elements the stream should be limited to
     *
     * @return Stream<TValue> The new stream
     *
     * @throws InvalidArgumentException if maxSize is negative
     */
    public function limit(int $maxSize): Stream;

    /**
     * Returns a stream consisting of the results of applying the given function to the elements of this stream.
     *
     * This is an intermediate operation.
     *
     * @template UValue
     *
     * @param callable(TValue): UValue $mapper a function to apply to each element
     *
     * @return Stream<UValue> The new stream
     */
    public function map(callable $mapper): Stream;

    /**
     * Returns the maximum element of this stream according to the provided comparator.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a comparator to compare elements of this stream
     *
     * @return Optional<TValue> an Optional describing the maximum element of this stream, or an empty Optional if the stream is empty
     */
    public function max(callable|Comparator|null $comparator = null): Optional;

    /**
     * Returns the minimum element of this stream according to the provided comparator.
     *
     * This is a terminal operation.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a comparator to compare elements of this stream
     *
     * @return Optional<TValue> an Optional describing the maximum element of this stream, or an empty Optional if the stream is empty
     */
    public function min(callable|Comparator|null $comparator = null): Optional;

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
     * @return bool `true` if either no elements of the stream match the provided predicate or the stream is empty, otherwise `false`
     */
    public function noneMatch(callable $predicate): bool;

    /**
     * Returns a stream consisting of the elements of this stream, additionally performing the provided action for each
     * element as elements are consumed from the resulting stream.
     *
     * This is an intermediate operation.
     *
     * @param callable(TValue): void $action a non-interfering action to perform on the elements as they are consumed from the stream
     *
     * @return Stream<TValue> The new stream
     */
    public function peek(callable $action): Stream;

    /**
     * Returns a stream consisting of the remaining elements of this stream after discarding the first n elements of the stream.
     *
     * If this stream contains fewer than n elements then an empty stream will be returned.
     *
     * This is an intermediate operation.
     *
     * @param int<0, max> $num the number of leading elements to skip
     *
     * @return Stream<TValue> The new stream
     *
     * @throws InvalidArgumentException if num is negative
     */
    public function skip(int $num): Stream;

    /**
     * Returns a stream consisting of the elements of this stream, sorted according to the provided comparator.
     *
     * This is an intermediate operation.
     *
     * @param callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a non-interfering comparator to be used to compare stream elements
     *
     * @return Stream<TValue> The new stream
     */
    public function sorted(callable|Comparator|null $comparator = null): Stream;

    /**
     * Transform this stream to an array.
     *
     * This is a terminal operation.
     *
     * @return TValue[]
     */
    public function toArray(): array;

    /**
     * Returns an `Optional` describing the first element of this stream, or an empty `Optional` if the stream is empty. If the stream has no encounter order, then any element may be returned.
     *
     * This is a terminal operation.
     *
     * @return Optional<TValue> an `Optional` describing the first element of this stream, or an empty `Optional` if the stream is empty
     */
    public function findFirst(): Optional;

    /**
     * Performs a reduction on the elements of this stream, using the provided identity value and an associative accumulation function, and returns the reduced value.
     *
     * This is equivalent to:
     * ```
     * $result = $initialValue;
     * foreach ($stream as $element) {
     *    $result = $accumulator($element, $result);
     * }
     * return $result;
     * ```
     *
     * This is a terminal operation.
     *
     * @template UValue
     *
     * @param UValue $initialValue the initial carried value to pass to the accumulator as second argument
     * @param callable(TValue, UValue): UValue $accumulator an associative, non-interfering, stateless function for combining two values
     *
     * @return UValue
     */
    public function reduce(mixed $initialValue, callable $accumulator): mixed;
}
