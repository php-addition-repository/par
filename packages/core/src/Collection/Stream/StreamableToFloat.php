<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

/**
 * TODO.
 *
 * @internal
 *
 * @template TValue
 *
 * @mixin Stream<TValue>
 */
interface StreamableToFloat
{
    /**
     * TODO.
     *
     * @param callable(TValue): float|null $toFloatMapper TODO
     */
    public function mapToFloat(?callable $toFloatMapper = null): FloatStream;
}
