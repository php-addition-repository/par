<?php

declare(strict_types=1);

namespace Par\Core\PHPUnit;

use Par\Core\Equable;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

final class EquableComparator extends Comparator
{
    public function accepts(mixed $expected, mixed $actual): bool
    {
        return $expected instanceof Equable && (is_object($actual) || is_null($actual));
    }

    public function assertEquals(
        mixed $expected,
        mixed $actual,
        float $delta = 0.0,
        bool $canonicalize = false,
        bool $ignoreCase = false
    ): void {
        assert($expected instanceof Equable);
        assert(is_null($actual) || is_object($actual));

        if (!$expected->equals($actual)) {
            $exporter = new Exporter();

            throw new ComparisonFailure(
                $expected,
                $actual,
                $exporter->export($expected),
                $exporter->export($actual),
                'Failed asserting that two objects are equal.'
            );
        }
    }
}
