<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use ArrayIterator;
use Generator;
use loophp\collection\Operation\Append;
use loophp\collection\Operation\Filter;
use loophp\collection\Operation\Flip;
use loophp\collection\Operation\Head;
use loophp\collection\Operation\Pipe;
use loophp\collection\Operation\Reverse;
use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;
use Par\Core\Exception\IndexOutOfBoundsException;
use Par\Core\Exception\NoSuchElementException;
use Par\Core\Values;
use Traversable;

/**
 * Implementation of a sequence backed by a native array.
 *
 * @internal
 *
 * @template TValue
 *
 * @implements MutableSequence<TValue>
 */
final class ArraySequence implements MutableSequence
{
    /**
     * @var array<int, TValue> internal array used to store the values of the sequence
     */
    protected array $inner;

    /**
     * @param iterable<TValue> $iterable
     */
    final private function __construct(iterable $iterable)
    {
        if (is_array($iterable)) {
            $this->inner = array_values($iterable);
        } else {
            $this->inner = [];
            foreach ($iterable as $element) {
                $this->inner[] = $element;
            }
        }
    }

    /**
     * Creates a vector without any elements.
     *
     * @return static<mixed> an empty vector
     */
    public static function empty(): self
    {
        return new static([]);
    }

    /**
     * Creates a vector containing the elements of the specified iterable, in the order they are returned by it.
     *
     * @template UValue
     *
     * @param iterable<UValue> $iterable iterable whose elements are to be placed into this vector
     *
     * @return static<UValue> A vector containing all values from the provided iterable in the order
     */
    public static function fromIterable(iterable $iterable): self
    {
        return new static($iterable);
    }

    public function contains(mixed $element): bool
    {
        return $this->stream()->anyMatch(
            static fn(mixed $value): bool => Values::equals($value, $element)
        );
    }

    public function containsAll(iterable $elements): bool
    {
        if ($elements instanceof Generator) {
            $elements = Stream::fromGenerator($elements);
        } else {
            $elements = Stream::fromIterable($elements);
        }

        return $elements->allMatch(
            fn(mixed $element): bool => $this->contains($element)
        );
    }

    public function count(): int
    {
        return count($this->inner);
    }

    public function first(): mixed
    {
        $this->guardNotEmpty();

        return $this->inner[0];
    }

    public function get(int $index): mixed
    {
        $this->guardIndexExists($index);

        return $this->inner[$index];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->inner);
    }

    public function indexOf(mixed $element): int
    {
        $steps = [
            Filter::of()(static fn(mixed $internalElement): bool => Values::equals($internalElement, $element)),
            Flip::of(),
            Append::of()([-1]),
            Head::of(),
        ];

        return Pipe::of()(...$steps)($this->inner)->current();
    }

    /**
     * @phpstan-assert-if-false non-empty-array<int<0, max>, TValue> $this->inner
     */
    public function isEmpty(): bool
    {
        return !array_key_exists(0, $this->inner);
    }

    public function last(): mixed
    {
        $this->guardNotEmpty();

        return $this->inner[count($this) - 1];
    }

    public function lastIndexOf(mixed $element): int
    {
        $steps = [
            Reverse::of(),
            Filter::of()(static fn(mixed $internalElement): bool => Values::equals($internalElement, $element)),
            Flip::of(),
            Append::of()([-1]),
            Head::of(),
        ];

        return Pipe::of()(...$steps)($this->stream())->current();
    }

    public function reversed(): static
    {
        return new static(array_reverse($this->inner));
    }

    public function sorted(callable|Comparator|null $comparator = null): static
    {
        return new static($this->stream()->sorted($comparator));
    }

    public function stream(): Stream
    {
        return Stream::fromIterable($this);
    }

    public function toArray(): array
    {
        return $this->inner;
    }

    public function add(mixed $element): bool
    {
        $this->inner[] = $element;

        return true;
    }

    public function addAll(iterable $elements): bool
    {
        $added = false;
        foreach ($elements as $element) {
            $added = $this->add($element);
        }

        return $added;
    }

    public function addFirst(mixed $element): void
    {
        array_unshift($this->inner, $element);
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

        array_shift($this->inner);

        return $current;
    }

    public function removeIf(callable $predicate): bool
    {
        $currentNum = $this->count();
        $this->inner = Stream::fromIterable($this->inner)
                             ->filter($predicate)
                             ->toArray();

        return $currentNum != $this->count();
    }

    public function removeLast(): mixed
    {
        $this->guardNotEmpty();

        $current = $this->last();

        array_pop($this->inner);

        return $current;
    }

    public function reverse(): static
    {
        $this->inner = array_reverse($this->inner);

        return $this;
    }

    public function set(int $index, mixed $element): mixed
    {
        $this->guardIndexExists($index);

        $current = $this->get($index);
        $this->inner[$index] = $element;

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
            array_splice($this->inner, $index, 0, $elementValues);
        }

        return $changed;
    }

    public function sort(callable|Comparator|null $comparator = null): static
    {
        if (!$comparator instanceof Comparator) {
            $comparator = is_callable($comparator) ? Comparators::with($comparator) : Comparators::values();
        }

        usort($this->inner, static fn(mixed $a, $b): int => $comparator->compare($a, $b)->value);

        return $this;
    }

    public function unset(int $index): mixed
    {
        $this->guardIndexExists($index);

        $current = $this->get($index);

        array_splice($this->inner, $index, 1);

        return $current;
    }

    protected function guardIndexExists(int $index): void
    {
        if ($index < 0 || !array_key_exists($index, $this->inner)) {
            throw IndexOutOfBoundsException::fromIndex($index);
        }
    }

    protected function guardNotEmpty(): void
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException(sprintf('%s is empty', static::class));
        }
    }
}
