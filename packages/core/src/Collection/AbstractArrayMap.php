<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use ArrayAccess;
use ArrayIterator;
use Par\Core\Collection\Traits\MapTrait;
use Par\Core\Exception\InvalidTypeException;
use Par\Core\Exception\NoSuchElementException;
use Traversable;

/**
 * @template TKey of array-key
 * @template TValue
 * @implements Map<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 */
abstract class AbstractArrayMap implements Map, ArrayAccess
{
    /**
     * @use MapTrait<TKey, TValue>
     */
    use MapTrait;

    /**
     * @var array<TKey, TValue>
     */
    protected array $internalMap;

    /**
     * @param array<TKey, TValue> $internalMap
     */
    final private function __construct(array $internalMap)
    {
        $this->internalMap = $internalMap;
    }

    /**
     * TODO
     *
     * @return static<array-key, mixed>
     */
    public static function empty(): self
    {
        return new static([]);
    }

    /**
     * TODO
     * @template UKey of array-key
     * @template UValue
     *
     * @param array<UKey, UValue> $map TODO
     *
     * @return static<UKey, UValue>
     */
    public static function fromArray(array $map): self
    {
        return new static($map);
    }

    /**
     * TODO
     *
     * @template UKey of array-key
     * @template UValue
     * @param iterable<UKey, UValue> $map TODO
     *
     * @return static<UKey, UValue>
     */
    public static function fromIterable(iterable $map): self
    {
        $array = [];
        $i = 0;
        foreach ($map as $key => $value) {
            self::guardItemArrayKey($i, $key);
            $array[$key] = $value;
            $i++;
        }

        return new static($array);
    }

    /**
     * @param mixed $key
     *
     * @return void
     * @phpstan-assert TKey $key
     */
    protected static function guardArrayKey(mixed $key): void
    {
        if (!is_int($key) && !is_string($key)) {
            throw InvalidTypeException::forValue($key, 'int|string');
        }
    }

    /**
     * @param int $index
     * @param mixed $key
     *
     * @return void
     * @phpstan-assert TKey $key
     */
    protected static function guardItemArrayKey(int $index, mixed $key): void
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
        return false;
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
     * @inheritDoc
     * @throws InvalidTypeException if `$offset` is of an inappropriate type for this map
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @inheritDoc
     * @throws NoSuchElementException if this map contains no mapping for the key
     * @throws InvalidTypeException if `$offset` is of an inappropriate type for this map
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function stream(): Stream
    {
        return Stream::fromIterable($this->internalMap);
    }

    public function toArray(): array
    {
        return $this->internalMap;
    }

    /**
     * @inheritDoc
     * @return Vector<TValue>
     */
    public function values(): Vector
    {
        return Vector::fromIterable(array_values($this->internalMap));
    }
}
