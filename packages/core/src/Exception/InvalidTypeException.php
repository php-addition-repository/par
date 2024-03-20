<?php

declare(strict_types=1);

namespace Par\Core\Exception;

class InvalidTypeException extends RuntimeException
{
    public static function forIndexedValue(int $index, mixed $value, string $expectedType): self
    {
        return new self(
            sprintf(
                'Expected a value of type %s, got %s at index %d',
                $expectedType,
                gettype($value),
                $index
            )
        );
    }

    public static function forValue(mixed $value, string $expectedType): self
    {
        return new self(
            sprintf(
                'Expected a value of type %s, got %s',
                $expectedType,
                gettype($value)
            )
        );
    }
}
