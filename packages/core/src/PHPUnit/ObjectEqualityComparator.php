<?php

declare(strict_types=1);

namespace Par\Core\PHPUnit;

use Par\Core\ObjectEquality;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

final class ObjectEqualityComparator extends Comparator
{
    public function accepts(mixed $expected, mixed $actual): bool
    {
        return $expected instanceof ObjectEquality || $actual instanceof ObjectEquality;
    }

    public function assertEquals(
        mixed $expected,
        mixed $actual,
        float $delta = 0.0,
        bool $canonicalize = false,
        bool $ignoreCase = false
    ): void {
        $right = $expected;
        $left = $actual;

        if ($expected instanceof ObjectEquality) {
            $left = $expected;
            $right = $actual;
        }

        assert($left instanceof ObjectEquality);

        if (!$left->equals($right)) {
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
