<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Collection\Traits\MutableMapTrait;
use Par\Core\Optional;

/**
 * TODO
 * @template TKey of array-key
 * @template TValue
 *
 * @implements MutableMap<TKey, TValue>
 * @extends AbstractArrayMap<TKey, TValue>
 */
final class MutableArrayMap extends AbstractArrayMap implements MutableMap
{
    /**
     * @use MutableMapTrait<TKey, TValue>
     */
    use MutableMapTrait;

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
}
