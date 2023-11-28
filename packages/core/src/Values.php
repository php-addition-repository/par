<?php

declare(strict_types=1);

namespace Par\Core;

use DateTimeInterface;

final class Values
{
    public static function equals(mixed $value, mixed $otherValue): bool
    {
        if ($value instanceof ObjectEquality) {
            return $value->equals($otherValue);
        }

        if ($otherValue instanceof ObjectEquality) {
            return $otherValue->equals($value);
        }

        if ($value instanceof DateTimeInterface && $otherValue instanceof DateTimeInterface) {
            return self::compareDateTimes($value, $otherValue);
        }

        return $value === $otherValue;
    }

    /**
     * @param DateTimeInterface $value
     * @param DateTimeInterface $otherValue
     * @return bool
     */
    private static function compareDateTimes(DateTimeInterface $value, DateTimeInterface $otherValue): bool
    {
        $comparisonFormat = DateTimeInterface::RFC3339_EXTENDED;
        return $value->format($comparisonFormat) === $otherValue->format($comparisonFormat);
    }
}
