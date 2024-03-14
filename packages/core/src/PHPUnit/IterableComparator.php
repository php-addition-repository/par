<?php

declare(strict_types=1);

namespace Par\Core\PHPUnit;

use Iterator;
use loophp\iterators\IterableIteratorAggregate;
use loophp\iterators\MultipleIterableAggregate;
use loophp\iterators\PackIterableAggregate;
use MultipleIterator;
use Par\Core\Assert;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

final class IterableComparator extends Comparator
{
    public function __construct(private readonly int $limit = 0)
    {
    }

    public function accepts(mixed $expected, mixed $actual): bool
    {
        if (is_array($expected) && is_array($actual)) {
            return false;
        }

        return is_iterable($expected) && is_iterable($actual);
    }

    public function assertEquals(
        mixed $expected,
        mixed $actual,
        float $delta = 0.0,
        bool $canonicalize = false,
        bool $ignoreCase = false,
        array &$processed = []
    ): void {
        Assert::isIterable($expected);
        Assert::isIterable($actual);

        // don't compare twice to allow for cyclic dependencies
        if (
            in_array([$actual, $expected], $processed, true)
            || in_array([$expected, $actual], $processed, true)
        ) {
            return;
        }

        $processed[] = [$actual, $expected];

        [$expectedIterable, $actualIterable] = array_map(
            static fn(iterable $iterable): Iterator => (new IterableIteratorAggregate($iterable))->getIterator(),
            [$expected, $actual]
        );

        $mi = new PackIterableAggregate(
            new MultipleIterableAggregate([$expectedIterable, $actualIterable], MultipleIterator::MIT_NEED_ALL)
        );

        $exporter = new Exporter();

        $index = null;
        foreach ($mi as $index => [$key, $value]) {
            if (0 !== $this->limit && $index >= $this->limit) {
                break;
            }

            $this->assertItemEquals(
                'key',
                $index,
                $key[0],
                $key[1],
                $delta,
                $canonicalize,
                $ignoreCase,
                $processed,
                $expected,
                $actual,
                $exporter
            );

            $this->assertItemEquals(
                'value',
                $index,
                $value[0],
                $value[1],
                $delta,
                $canonicalize,
                $ignoreCase,
                $processed,
                $expected,
                $actual,
                $exporter
            );
        }

        $expectedValid = $expectedIterable->valid();
        $actualValid = $actualIterable->valid();

        if ($expectedValid !== $actualValid) {
            if ($expectedValid) {
                throw new ComparisonFailure(
                    $expected,
                    $actual,
                    substr_replace($exporter->export($this->generateIndexesDiff($index, true)), 'iterable', 0, 8),
                    substr_replace($exporter->export($this->generateIndexesDiff($index, false)), 'iterable', 0, 8),
                    'Expected iterable has more items than actual.'
                );
            }

            throw new ComparisonFailure(
                $expected,
                $actual,
                substr_replace($exporter->export($this->generateIndexesDiff($index, false)), 'iterable', 0, 8),
                substr_replace($exporter->export($this->generateIndexesDiff($index, true)), 'iterable', 0, 8),
                'Expected iterable has lesser items than actual.'
            );
        }
    }

    private function assertItemEquals(
        string $itemPart,
        int $index,
        mixed $expectedItem,
        mixed $actualItem,
        float $delta,
        bool $canonicalize,
        bool $ignoreCase,
        array &$processed,
        iterable $expected,
        iterable $actual,
        Exporter $exporter
    ): void {
        try {
            $comparator = $this->factory()->getComparatorFor($expectedItem, $actualItem);
            /* @psalm-suppress TooManyArguments */
            $comparator->assertEquals(
                $expectedItem,
                $actualItem,
                $delta,
                $canonicalize,
                $ignoreCase,
                $processed
            );
        } catch (ComparisonFailure $e) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                '' === $e->getExpectedAsString() ? $exporter->export($e->getExpected()) : $e->getExpectedAsString(),
                '' === $e->getActualAsString() ? $exporter->export($e->getActual()) : $e->getActualAsString(),
                sprintf('Expected iterable %s is different from actual %s at index %d', $itemPart, $itemPart, $index)
            );
        }
    }

    private function generateIndexesDiff(?int $index, bool $addOne): array
    {
        $indexes = [];
        $value = '...';

        if ($addOne && is_null($index)) {
            $addOne = false;
            $index = 0;
        }

        if (!is_null($index)) {
            $indexes[$index] = $value;

            if ($addOne) {
                $indexes[$index + 1] = $value;
            }
        }

        return $indexes;
    }
}
