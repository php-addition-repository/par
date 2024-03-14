<?php

declare(strict_types=1);

namespace Par\Core;

use DateTimeInterface;
use Par\Core\Exception\AssertionFailedException;

abstract class Assert extends \Webmozart\Assert\Assert
{
    protected const MAX_SHORT_LENGTH = 100;

    /**
     * @throws AssertionFailedException
     */
    protected static function reportInvalidArgument($message): never
    {
        throw new AssertionFailedException($message);
    }

    protected static function valueToString(mixed $value): string
    {
        if (null === $value) {
            return 'null';
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        if (is_array($value) && empty($value)) {
            return 'array:empty';
        }

        if (is_array($value)) {
            return array_is_list($value) ? 'array:list' : 'array:assoc';
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return sprintf('%s(%s)', $value::class, static::valueToString($value->__toString()));
            }

            if ($value instanceof DateTimeInterface) {
                return sprintf('%s(%s)', $value::class, static::valueToString($value->format('c')));
            }

            return $value::class;
        }

        if (is_resource($value)) {
            return 'resource';
        }

        if (is_string($value)) {
            $value = \preg_replace('/\s+/', ' ', $value);

            if (\mb_strlen($value) > static::MAX_SHORT_LENGTH) {
                $value = \sprintf(
                    '%s...%s',
                    \mb_substr($value, 0, (int) ceil(static::MAX_SHORT_LENGTH / 2) - 3),
                    \mb_substr($value, (int) floor(static::MAX_SHORT_LENGTH / 2) * -1)
                );
            }

            return sprintf('"%s"', $value);
        }

        return strval($value);
    }
}
