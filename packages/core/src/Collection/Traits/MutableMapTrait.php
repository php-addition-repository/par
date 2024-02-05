<?php

declare(strict_types=1);

namespace Par\Core\Collection\Traits;

use Par\Core\Collection\Map;
use Par\Core\Collection\MutableMap;
use Par\Core\Optional;

/**
 * @template TKey
 * @template TValue
 * @mixin MutableMap<TKey, TValue>
 */
trait MutableMapTrait
{
    /**
     * @inheritDoc
     */
    public function compute(mixed $key, callable $remappingFunction): Optional
    {
        $oldValue = $this->getOrDefault($key);
        $newValue = $remappingFunction($key, $oldValue);
        if (null !== $newValue) {
            $this->put($key, $newValue);
        } elseif (null !== $oldValue || $this->containsKey($key)) {
            $this->remove($key);
        }

        return Optional::fromNullable($newValue);
    }

    /**
     * @inheritDoc
     */
    public function computeIfAbsent(mixed $key, callable $remappingFunction): Optional
    {
        $newValue = null;
        if (!$this->containsKey($key)) {
            $newValue = $remappingFunction($key);
            if (null !== $newValue) {
                $this->put($key, $newValue);
            }
        }

        return Optional::fromNullable($newValue);
    }

    /**
     * @inheritDoc
     */
    public function computeIfPresent(mixed $key, callable $remappingFunction): Optional
    {
        if ($this->containsKey($key)) {
            $oldValue = $this->get($key);
            $newValue = $remappingFunction($key, $oldValue);
            if (null !== $newValue) {
                $this->put($key, $newValue);
            } else {
                $this->remove($key);
            }

            return Optional::fromAny($newValue);
        }

        return Optional::empty();
    }

    /**
     * @inheritDoc
     */
    public function putAll(Map $map): void
    {
        foreach ($map as $key => $value) {
            $this->put($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function putIfAbsent(mixed $key, mixed $value): Optional
    {
        if (!$this->containsKey($key)) {
            return $this->put($key, $value);
        }

        return Optional::empty();
    }
}
