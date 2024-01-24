<?php

declare(strict_types=1);

namespace Par\CoreTest\Fixtures;

/**
 * This interface is here to allow for mocking a callable.
 *
 * ```php
 * $invoker = $this->createMock(Invokable::class);
 * $matcher = $this->exactly(5);
 * $invoker->expects($matcher)
 *     ->method('__invoke')
 *     ->willReturnCallback(function (int $value) use ($matcher) {
 *         match ($matcher->numberOfInvocations()) {
 *             1 =>  $this->assertEquals(1, $value),
 *             2 =>  $this->assertEquals(2, $value),
 *             3 =>  $this->assertEquals(3, $value),
 *             4 =>  $this->assertEquals(4, $value),
 *             5 =>  $this->assertEquals(5, $value),
 *         };
 *     });
 * ```
 */
interface Invokable
{
    public function __invoke(): void;
}
