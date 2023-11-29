<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit;

use PHPUnit\Framework\TestCase;

class FailingTest extends TestCase
{
    public function testItReportsFailure()
    {
        $this->assertFalse(false);
    }
}
