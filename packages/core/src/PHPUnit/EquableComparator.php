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
        return $expected instanceof Equable || $actual instanceof Equable;
    }

    /**
     * @param mixed|Equable $expected
     * @param mixed|Equable $actual
     */
    public function assertEquals(
        mixed $expected,
        mixed $actual,
        float $delta = 0.0,
        bool $canonicalize = false,
        bool $ignoreCase = false
    ): void
    {
        if ($expected instanceof Equable) {
            $left = $expected;
            $right = $actual;
        } else {
            $right = $expected;
            /** @var Equable $actual */
            $left = $actual;
        }

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
