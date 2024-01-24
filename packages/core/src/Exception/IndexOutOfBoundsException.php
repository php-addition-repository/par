<?php

declare(strict_types=1);

namespace Par\Core\Exception;

use OutOfBoundsException;

final class IndexOutOfBoundsException extends OutOfBoundsException implements ExceptionInterface
{
    public static function fromIndex(int $index): self
    {
        return new self($index);
    }

    private function __construct(public readonly int $index)
    {
        parent::__construct(sprintf('Index %d is out of bounds', $index));
    }
}
