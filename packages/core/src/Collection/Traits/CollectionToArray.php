<?php

declare(strict_types=1);

namespace Par\Core\Collection\Traits;

use Par\Core\Collection\Collection;
use Par\Core\Exception\InvalidTypeException;

/**
 * @template TKey
 * @template TValue
 *
 * @mixin Collection<TKey, TValue>
 */
trait CollectionToArray
{
    public function toArray(): array
    {
        $array = [];

        $format = 'A PHP array only supports keys of type "string|int", this map contains one or more keys of type %s';

        foreach ($this as $key => $value) {
            if (!is_int($key) && !is_string($key)) {
                throw new InvalidTypeException(
                    sprintf(
                        $format,
                        gettype($key)
                    )
                );
            }
            $array[$key] = $value;
        }

        return $array;
    }
}
