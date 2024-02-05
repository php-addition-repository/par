<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use loophp\collection\Operation\Append;
use loophp\collection\Operation\Drop;
use loophp\collection\Operation\First;
use loophp\collection\Operation\Flip;
use loophp\collection\Operation\MatchOne;
use loophp\collection\Operation\Pipe;
use loophp\collection\Operation\Reverse;
use Par\Core\Comparison\Comparator;
use Par\Core\Exception\IndexOutOfBoundsException;
use Par\Core\Exception\NoSuchElementException;
use Par\Core\Values;
use Traversable;

/**
 * Abstract implementation of a sequence.
 *
 * @internal
 * @template TValue
 * @implements Sequence<TValue>
 */
abstract class AbstractVector implements Sequence
{
    /**
     * @var array<int<0,max>, TValue> internal array used to store the values of the sequence.
     */
    protected array $array = [];

    /**
     * @param iterable<TValue> $iterable
     */
    final private function __construct(iterable $iterable)
    {
        foreach ($iterable as $value) {
            $this->array[] = $value;
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
        return Values::equalsAnyIn($element, $this->array);
    }

    public function containsAll(iterable $elements): bool
    {
        foreach ($elements as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }

        return true;
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function first(): mixed
    {
        $this->guardNotEmpty();

        return $this->array[0];
    }

    public function get(int $index): mixed
    {
        $this->guardIndexExists($index);

        return $this->array[$index];
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * @inheritDoc
     */
    public function indexOf(mixed $element, int $index = null): int
    {
        $this->guardIndexExists($index);

        $pipe = Pipe::of()(
            Drop::of()($index === null ? 0 : $index),
            MatchOne::of()(static fn(mixed $internalElement): bool => Values::equals($internalElement, $element)),
            Flip::of()(),
            Append::of()(-1),
            First::of()()
        );

        return $pipe($this->array)->first();
    }

    /**
     * @inheritDoc
     * @return bool
     * @phpstan-assert-if-false non-empty-array<int<0, max>, TValue> $this->array
     */
    public function isEmpty(): bool
    {
        return empty($this->array);
    }

    public function last(): mixed
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }

        return $this->array[count($this->array) - 1];
    }

    /**
     * @inheritDoc
     */
    public function lastIndexOf(mixed $element, int $index = null): int
    {
        $this->guardIndexExists($index);

        $pipe = Pipe::of()(
            Reverse::of()(),
            Drop::of()($index === null ? 0 : count($this) - $index - 1),
            MatchOne::of()(static fn(mixed $internalElement): bool => Values::equals($internalElement, $element)),
            Flip::of()(),
            Append::of()(-1),
            First::of()()
        );

        return $pipe($this->array)->first();
    }

    public function reversed(): static
    {
        return new static(array_reverse($this->array));
    }

    public function sorted(callable|Comparator $comparator = null): static
    {
        return new static(
            Stream::fromIterable($this->array)->sorted($comparator)->toArray()
        );
    }

    public function stream(): Stream
    {
        return Stream::fromIterable($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }

    protected function guardIndexExists(?int $index): void
    {
        if (is_int($index) && ($index < 0 || $index >= count($this))) {
            throw IndexOutOfBoundsException::fromIndex($index);
        }
    }

    protected function guardNotEmpty(): void
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException(sprintf('%s is empty', self::class));
        }
    }
}
