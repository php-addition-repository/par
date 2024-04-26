<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

/**
 * This enum represents the value of something when compared to another.
 */
enum Order: int
{
    case Lesser = -1;
    case Equal = 0;
    case Greater = 1;

    /**
     * Returns the inverted value of current `Par\Core\Comparison\Order`.
     */
    public function invert(): self
    {
        return match ($this) {
            self::Equal => $this,
            self::Lesser => self::Greater,
            self::Greater => self::Lesser
        };
    }
}
