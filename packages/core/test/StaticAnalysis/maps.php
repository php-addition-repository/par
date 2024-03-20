<?php

declare(strict_types=1);

namespace Par\CoreTest\StaticAnalysis;

use Par\Core\Collection\ArrayMap;

/* @noinspection PhpIllegalPsrClassPathInspection */

final class TestObject
{
}

/** @var int[] $range */
$range = range(1, 5);
$map = ArrayMap::fromArray($range);
foreach ($map as $key => $value) {
    ExpectType::int($key);
    ExpectType::int($value);
}

/** @var array<string, TestObject> $arrayMap */
$arrayMap = ['foo' => new TestObject(), 'bar' => new TestObject()];
$map = ArrayMap::fromArray($arrayMap);
foreach ($map as $key => $value) {
    ExpectType::string($key);
    ExpectType::instanceOf(TestObject::class, $value);
}
