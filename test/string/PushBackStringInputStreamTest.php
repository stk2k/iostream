<?php
declare(strict_types=1);

namespace stk2k\iostream\test\string;

use PHPUnit\Framework\TestCase;
use stk2k\iostream\string\PushBackStringInputStream;
use stk2k\xstring\xStringBuffer;

final class PushBackStringInputStreamTest extends TestCase
{
    public function testUnread()
    {
        $pbsis = new PushBackStringInputStream(', World!');

        $pbsis->unread(new xStringBuffer('olleH'));

        $this->assertEquals('Hello, World!', $pbsis->read(999));
    }
}