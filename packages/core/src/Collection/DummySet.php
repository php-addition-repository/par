<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use EmptyIterator;
use Traversable;

/**
 * @template TValue
 * @implements Set<TValue>
 */
final class DummySet implements Set
{
    /**
     * TODO
     * @template UValue
     *
     * @param iterable<UValue> $iterable
     *
     * @return self<UValue>
     */
    public static function fromIterable(iterable $iterable): self
    {
        return new self();
    }

    public function contains(mixed $element): bool
    {
        // TODO: Implement contains() method.
        return false;
    }

    public function containsAll(iterable $elements): bool
    {
        // TODO: Implement containsAll() method.
        return false;
    }

    public function count(): int
    {
        // TODO: Implement count() method.
        return 0;
    }

    public function getIterator(): Traversable
    {
        // TODO: Implement getIterator() method.
        return new EmptyIterator();
    }

    public function isEmpty(): bool
    {
        // TODO: Implement isEmpty() method.
        return true;
    }

    public function stream(): Stream
    {
        // TODO: Implement stream() method.
        return Stream::empty();
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        return [];
    }
}
