<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use ArrayAccess;
use ArrayIterator;
use Par\Core\Collection\Stream\MixedStream;
use Par\Core\Collection\Stream\Stream;
use Par\Core\Collection\Traits\MapTrait;
use Par\Core\Collection\Traits\MutableMapTrait;
use Par\Core\Exception\InvalidTypeException;
use Par\Core\Exception\NoSuchElementException;
use Par\Core\Optional;
use Traversable;

/**
 * TODO.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @implements MutableMap<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 */
final class ArrayMap implements MutableMap, ArrayAccess
{
    /**
     * @use MapTrait<TKey, TValue>
     */
    use MapTrait;

    /**
     * @use MutableMapTrait<TKey, TValue>
     */
    use MutableMapTrait;

    /**
     * @param array<TKey, TValue> $internalMap
     */
    private function __construct(private array $internalMap)
    {
    }

    /**
     * TODO.
     *
     * @return static<string|int, mixed> TODO
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * TODO.
     *
     * @param array<TKey, TValue> $map array map to use as source for this ArrayMap
     *
     * @return self<TKey, TValue> TODO
     */
    public static function fromArray(array $map): self
    {
        return new self($map);
    }

    /**
     * TODO.
     *
     * @param iterable<TKey, TValue> $map TODO
     *
     * @return self<TKey, TValue> TODO
     *
     * @throws InvalidTypeException if any key or value of the provided iterable is of an inappropriate type for this map
     */
    public static function fromIterable(iterable $map): self
    {
        $array = [];
        $i = 0;
        foreach ($map as $key => $value) {
            self::guardItemArrayKey($i, $key);
            $array[$key] = $value;
            ++$i;
        }

        return new self($array);
    }

    /**
     * @phpstan-assert TKey $key
     */
    private static function guardArrayKey(mixed $key): void
    {
        if (!is_int($key) && !is_string($key)) {
            throw InvalidTypeException::forValue($key, 'int|string');
        }
    }

    /**
     * @phpstan-assert TKey $key
     */
    private static function guardItemArrayKey(int $index, mixed $key): void
    {
        if (!is_int($key) && !is_string($key)) {
            throw InvalidTypeException::forIndexedValue($index, $key, 'int|string');
        }
    }

    public function containsKey(mixed $key): bool
    {
        self::guardArrayKey($key);

        return array_key_exists($key, $this->internalMap);
    }

    public function containsValue(mixed $value): bool
    {
        return $this->values()->contains($value);
    }

    final public function count(): int
    {
        return count($this->internalMap);
    }

    public function get(mixed $key): mixed
    {
        if ($this->containsKey($key)) {
            return $this->internalMap[$key];
        }

        throw new NoSuchElementException();
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->internalMap);
    }

    public function isEmpty(): bool
    {
        return empty($this->internalMap);
    }

    public function keySet(): Set
    {
        return DummySet::fromIterable(array_keys($this->internalMap));
    }

    /**
     * @throws InvalidTypeException if `$offset` is of an inappropriate type for this map
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @throws NoSuchElementException if this map contains no mapping for the key
     * @throws InvalidTypeException if `$offset` is of an inappropriate type for this map
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        self::guardArrayKey($offset);

        $this->put($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        self::guardArrayKey($offset);

        $this->remove($offset);
    }

    public function put(mixed $key, mixed $value): Optional
    {
        self::guardArrayKey($key);

        $oldValue = Optional::empty();
        if ($this->containsKey($key)) {
            $oldValue = Optional::fromAny($this->internalMap[$key]);
        }

        $this->internalMap[$key] = $value;

        return $oldValue;
    }

    public function remove(mixed $key): Optional
    {
        self::guardArrayKey($key);

        $oldValue = Optional::empty();
        if ($this->containsKey($key)) {
            $oldValue = Optional::fromAny($this->internalMap[$key]);
        }

        unset($this->internalMap[$key]);

        return $oldValue;
    }

    public function stream(): Stream
    {
        return MixedStream::fromIterable($this->internalMap);
    }

    public function toArray(): array
    {
        return $this->internalMap;
    }

    /**
     * @return ArraySequence<TValue>
     */
    public function values(): ArraySequence
    {
        return ArraySequence::fromIterable($this->internalMap);
    }
}
