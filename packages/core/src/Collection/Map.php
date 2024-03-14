<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Countable;
use IteratorAggregate;
use Par\Core\Collection\Stream\Stream;
use Par\Core\Exception\InvalidTypeException;
use Par\Core\Exception\NoSuchElementException;
use Traversable;

/**
 * An object that maps keys to values. A map cannot contain duplicate keys; each key can map to at most one value.
 *
 * @template TKey the type of keys maintained by this map
 * @template TValue the type of mapped values
 *
 * @extends IteratorAggregate<TKey, TValue>
 */
interface Map extends IteratorAggregate, Countable
{
    /**
     * Returns `true` if this map contains a mapping for the specified key.
     *
     * More formally, returns `true` if and only if this map contains a mapping for a key `$k` such that
     * `Values->equals($key, $k)`. (There can be at most one such mapping.)
     *
     * @param TKey $key key whose presence in this map is to be tested
     *
     * @return bool `true` if this map contains a mapping for the specified key
     *
     * @throws InvalidTypeException if key is of an inappropriate type for this map
     */
    public function containsKey(mixed $key): bool;

    /**
     * Returns `true` if this map maps one or more keys to the specified value.
     *
     * @param TValue $value value whose presence in this map is to be tested
     *
     * @return bool `true` if this map maps one or more keys to the specified value
     *
     * @throws InvalidTypeException if value is of an inappropriate type for this map
     */
    public function containsValue(mixed $value): bool;

    /**
     * Returns the value to which the specified key is mapped.
     *
     * @param TKey $key the key whose associated value is to be returned
     *
     * @return TValue the value to which the specified key is mapped
     *
     * @throws InvalidTypeException if the key is of an inappropriate type for this map
     * @throws NoSuchElementException if this map contains no mapping for the key
     */
    public function get(mixed $key): mixed;

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable;

    /**
     * TODO.
     *
     * @template UValue
     *
     * @param TKey $key TODO
     * @param UValue|null $default TODO
     *
     * @return TValue|UValue
     *
     * @throws InvalidTypeException if the key is of an inappropriate type for this map
     */
    public function getOrDefault(mixed $key, mixed $default = null): mixed;

    /**
     * Returns `true` if this map contains no mappings.
     *
     * The implementation behind this method might be more optimized then `count($this) === 0` since counting the
     * map could require iterating over all mappings.
     *
     * @return bool `true` if this map contains no mappings
     */
    public function isEmpty(): bool;

    /**
     * Returns a Set of the keys contained in this map.
     *
     * @return Set<TKey> a set of the keys contained in this map
     */
    public function keySet(): Set;

    /**
     * Returns a stream with this map as its source.
     *
     * @return Stream<TValue>
     */
    public function stream(): Stream;

    /**
     * Returns an array containing all of the mapped keys and values of this map.
     *
     * If this map makes any guarantees as to what order its elements are returned by its iterator, this method
     * must return the elements in the same order.
     *
     * @return array<TKey, TValue> An array representation of this map
     *
     * @throws InvalidTypeException if the type of key invalid (such that `is_int($key) || is_string($key)` results in
     *                              `false`)
     */
    public function toArray(): array;

    /**
     * Returns a Sequence of the values contained in this map.
     *
     * @return Sequence<TValue> a sequence of the values contained in this map
     */
    public function values(): Sequence;
}
