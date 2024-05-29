<?php

declare(strict_types=1);

namespace Par\CoreTest\StaticAnalysis;

final class ExpectType
{
    public static function int(int $int): void
    {
    }

    public static function string(string $value): void
    {
    }

    public static function bool(bool $value): void
    {

    }
}
