<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;

/**
 * A mutable vector.
 *
 * @see Vector
 * @template TValue
 * @extends AbstractVector<TValue>
 * @implements MutableSequence<TValue>
 */
final class MutableVector extends AbstractVector implements MutableSequence
{
    public function add(mixed $element): void
    {
        $this->array[] = $element;
    }

    public function addAll(iterable $elements): bool
    {
        $changed = false;
        foreach ($elements as $element) {
            $this->array[] = $element;
            $changed = true;
        }

        return $changed;
    }

    public function addFirst(mixed $element): void
    {
        array_unshift($this->array, $element);
    }

    public function addLast(mixed $element): void
    {
        $this->add($element);
    }

    public function remove(mixed $element): bool
    {
        $index = $this->indexOf($element);

        if ($index < 0) {
            return false;
        }

        $this->unset($index);

        return true;
    }

    public function removeFirst(): mixed
    {
        $this->guardNotEmpty();

        $current = $this->first();

        array_shift($this->array);

        return $current;
    }

    public function removeIf(callable $predicate): bool
    {
        $currentNum = $this->count();
        $this->array = Stream::fromIterable($this->array)
            ->filter($predicate)
            ->toArray();

        return $currentNum != $this->count();
    }

    public function removeLast(): mixed
    {
        $this->guardNotEmpty();

        $current = $this->last();

        array_pop($this->array);

        return $current;
    }

    public function reverse(): self
    {
        $this->array = array_reverse($this->array);

        return $this;
    }

    public function set(int $index, mixed $element): mixed
    {
        $this->guardIndexExists($index);

        $current = $this->get($index);
        $this->array[$index] = $element;

        return $current;
    }

    public function setAll(int $index, iterable $elements): bool
    {
        $this->guardIndexExists($index);

        $elementValues = [];
        foreach ($elements as $element) {
            $elementValues[] = $element;
        }

        if ($changed = !empty($elementValues)) {
            array_splice($this->array, $index, 0, $elementValues);
        }

        return $changed;
    }

    public function sort(callable|Comparator $comparator = null): self
    {
        if (!$comparator instanceof Comparator) {
            $comparator = is_callable($comparator) ? Comparators::with($comparator) : Comparators::values();
        }

        usort($this->array, static fn(mixed $a, $b): int => $comparator->compare($a, $b)->value);

        return $this;
    }

    public function unset(int $index): mixed
    {
        $this->guardIndexExists($index);

        $current = $this->get($index);

        array_splice($this->array, $index, 1);

        return $current;
    }
}
