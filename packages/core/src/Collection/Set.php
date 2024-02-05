<?php

declare(strict_types=1);

namespace Par\Core\Collection;

/**
 * A collection that contains no duplicate elements.
 *
 * @template TValue
 *
 * @extends Collection<int<0, max>, TValue>
 */
interface Set extends Collection
{
}
