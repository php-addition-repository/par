<?php

declare(strict_types=1);

namespace Par\CoreTest\StaticAnalysis;

use Par\Core\Collection\Stream\FloatStream;
use Par\Core\Collection\Stream\IntStream;
use Par\Core\Collection\Stream\MixedStream;

$stream = MixedStream::fromIterable([1, 2, 3]);
ExpectType::instanceOf(MixedStream::class, $stream);

$mapped = $stream->map(static fn(int $value): int => $value);
ExpectType::instanceOf(MixedStream::class, $mapped);
ExpectType::allInts($mapped);

$intStream = IntStream::range(0, 10);
ExpectType::instanceOf(IntStream::class, $intStream);
ExpectType::allInts($intStream);

$toFloatStream = $intStream->mapToFloat();
ExpectType::instanceOf(FloatStream::class, $toFloatStream);
ExpectType::allFloats($toFloatStream);
