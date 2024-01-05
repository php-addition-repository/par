<?php

declare(strict_types=1);

namespace Par\Core\Exception;

use InvalidArgumentException as NativeInvalidArgumentException;

abstract class InvalidArgumentException extends NativeInvalidArgumentException implements ExceptionInterface
{

}
