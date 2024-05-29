<?php

declare(strict_types=1);

use Par\Core\Values;
use Par\CoreTest\StaticAnalysis\ExpectType;

// =======================
// Valid use cases
// =======================

ExpectType::bool(Values::equals(1, 1));
ExpectType::bool(Values::equals('foo', 'bar'));
ExpectType::bool(Values::equals(new DateTime(), new DateTime()));
ExpectType::bool(Values::equals(new DateTimeImmutable(), new DateTimeImmutable()));
ExpectType::bool(
    Values::equals(
        new stdClass(),
        new class() extends stdClass {
        }
    )
);

// =======================
// Invalid use cases
// =======================

/* @phpstan-ignore staticMethod.impossibleType (types never match, thus values are never equal) */
ExpectType::bool(Values::equals(1, 'foo'));

ExpectType::bool(
/* @phpstan-ignore staticMethod.impossibleType (classes are of different type) */
    Values::equals(
        new class() {
        },
        new class() {
        }
    )
);
