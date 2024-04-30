<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ValuesTest extends TestCase
{
    #[Test]
    public function itCanReturnSequenceOfValues(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        $sequence = $map->values();
        self::assertEquals(ArraySequence::fromIterable([1, 2, 3]), $sequence);

        $map->put('foobar', 4);
        self::assertEquals(ArraySequence::fromIterable([1, 2, 3]), $sequence);
    }
}
