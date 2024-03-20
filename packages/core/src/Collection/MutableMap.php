<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Exception\InvalidTypeException;
use Par\Core\Optional;

/**
 * A mutable map.
 *
 * @template TKey
 * @template TValue
 *
 * @extends Map<TKey, TValue>
 */
interface MutableMap extends Map
{
    /**
     * Attempts to compute a mapping for the specified key and its current mapped value (or `null` if there is not
     * current mapping).
     *
     * For example, to either create or append a string `$msg` to a value mapping:
     * ```php
     * $msg = 'msg';
     * $map->compute('foo', static fn('foo', ?string $value): string => $value === null ? $msg : $value.$msg);
     * ```
     *
     * If the remapping function returns `null`, the mapping is removed if the key is present in this map. Any
     * exception thrown from the remapping function is rethrown, and the current mapping is left unchanged.
     *
     * The remapping function should not modify this map during computation.
     *
     * @param TKey $key key with which the specified value is to be associated
     * @param callable(TKey, ?TValue): ?TValue $remappingFunction the remapping function to compute a value
     *
     * @return Optional<TValue> optional with the new value associated with the specified key, or an empty optional if
     *                          remapping returned `null`
     *
     * @throws InvalidTypeException if the key or remapped value is of an inappropriate type for this map
     */
    public function compute(mixed $key, callable $remappingFunction): Optional;

    /**
     * If the specified key is not already associated with a value, attempts to compute a mapping for the given key.
     *
     * If the remapping function returns `null`, the mapping is removed. Any exception thrown from the remapping
     * function is rethrown, and the current mapping is left unchanged.
     *
     * The remapping function should not modify this map during computation.
     *
     * @param TKey $key key with which the specified value is to be associated
     * @param callable(TKey): ?TValue $remappingFunction the remapping function to compute a value
     *
     * @return Optional<TValue> optional with the remapped value associated with the specified key, or an empty
     *                          optional if the remapping returned `null`
     *
     * @throws InvalidTypeException if the key or remapped value is of an inappropriate type for this map
     */
    public function computeIfAbsent(mixed $key, callable $remappingFunction): Optional;

    /**
     * If the value for the specified key is present, attempts to compute a new mapping given the key and its current
     * mapped value (which could be `null`).
     *
     * @param mixed $key key with which the specified value is to be associated
     * @param callable(TKey, ?TValue): ?TValue $remappingFunction the remapping function to compute a value
     *
     * @return Optional<TValue> optional with the new value associated with the specified key, or an empty optional if
     *                          the remapping returned `null`
     *
     * @throws InvalidTypeException if the key or remapped value is of an inappropriate type for this map
     */
    public function computeIfPresent(mixed $key, callable $remappingFunction): Optional;

    /**
     * Associates the specified value with the specified key in this map.
     *
     * @param TKey $key key with which the specified value is to be associated
     * @param TValue $value value to be associated with the specified key
     *
     * @return Optional<TValue> optional with the previous value associated with `$key`, or an empty optional if
     *                          there was no mapping for `$key`
     *
     * @throws InvalidTypeException if the key or value is of an inappropriate type for this map
     */
    public function put(mixed $key, mixed $value): Optional;

    /**
     * Copies all of the mappings from the specified map to this map.
     *
     * @param Map<TKey, TValue> $map mappings to be stored in this map
     *
     * @throws InvalidTypeException if any of the keys or values are of an inappropriate type for this map
     */
    public function putAll(Map $map): void;

    /**
     * If the specified key is not already associated with a value associated it with the given value.
     *
     * @template UValue of TValue
     *
     * @param TKey $key key with which the specified value is to be associated
     * @param UValue $value value to be associated with the specified key
     *
     * @return Optional<TValue> optional with the previous value associated with `$key`, or an empty optional if
     *                          there was no mapping for `$key`
     *
     * @throws InvalidTypeException if the key or value is of an inappropriate type for this map
     */
    public function putIfAbsent(mixed $key, mixed $value): Optional;

    /**
     * Removes the mapping for a key form this map if it is present.
     *
     * @param TKey $key key whose mapping is to be removed from the map
     *
     * @return Optional<TValue> optional with the previous value associated with `$key`, or an empty optional of there
     *                          was no mapping for `$key`
     *
     * @throws InvalidTypeException if the key is of an inappropriate type for this map
     */
    public function remove(mixed $key): Optional;
}
