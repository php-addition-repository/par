<?php

declare(strict_types=1);

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Order;

$strings = Comparators::with(static fn(string|Stringable $a, string|Stringable $b): Order => Order::from($a <=> $b));

$strings->compare('1', '2');

/* @phpstan-ignore argument.type */
$strings->compare(1, '2');

/* @phpstan-ignore argument.type */
$strings->compare('1', 2);

/** @phpstan-ignore argument.type */
Comparators::with(static fn(string $a, int $b): Order => Order::from($a <=> $b));
