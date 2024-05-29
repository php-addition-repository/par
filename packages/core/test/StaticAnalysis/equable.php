<?php

declare(strict_types=1);

use Par\Core\Equable;
use Par\CoreTest\StaticAnalysis\ExpectType;

/**
 * @implements Equable<NumberObject>
 */
class NumberObject implements Equable
{
    public function equals(?Equable $other): bool
    {
        return true;
    }
}

/**
 * @implements Equable<OtherNumberObject|NumberObject>
 */
class OtherNumberObject implements Equable
{
    public function equals(?Equable $other): bool
    {
        return true;
    }
}

/**
 * @implements Equable<OtherObject>
 */
class OtherObject implements Equable
{
    public function equals(?Equable $other): bool
    {
        return true;
    }
}

// =======================
// Valid use cases
// =======================

ExpectType::bool((new NumberObject())->equals(new NumberObject()));
ExpectType::bool((new OtherNumberObject())->equals(new NumberObject()));

// =======================
// Invalid use cases
// =======================

/* @phpstan-ignore argument.type */
ExpectType::bool((new NumberObject())->equals(new OtherObject()));

