<?php
declare(strict_types=1);

namespace stk2k\iostream;

use stk2k\xstring\xStringBuffer;

interface PushBackInputStreamInterface extends InputStreamInterface
{
    /**
     * Pushes back a character to stream
     *
     * @param xStringBuffer $buffer
     */
    public function unread(xStringBuffer $buffer) : void;
}