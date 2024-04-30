<?php

declare(strict_types=1);

namespace Par\Core\Collection\Traits;

use Par\Core\Collection\Map;

/**
 * @template TKey
 * @template TValue
 *
 * @mixin Map<TKey, TValue>
 */
trait MapTrait
{
    public function getOrDefault(mixed $key, mixed $default = null): mixed
    {
        if ($this->containsKey($key)) {
            return $this->get($key);
        }

        return $default;
    }
}
