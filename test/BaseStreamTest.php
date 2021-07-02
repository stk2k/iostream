<?php
declare(strict_types=1);

namespace stk2k\iostream\test;

use PHPUnit\Framework\TestCase;
use stk2k\iostream\BaseStream;

final class BaseStreamTest extends TestCase
{
    public function testClose()
    {
        $st = new BaseStream();

        $this->assertEquals(false, $st->isClosed());

        $st->close();

        $this->assertEquals(true, $st->isClosed());
    }
}