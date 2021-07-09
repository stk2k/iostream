<?php
declare(strict_types=1);

namespace stk2k\iostream\string;

use stk2k\iostream\PushBackInputStreamInterface;
use stk2k\xstring\xStringBuffer;

class PushBackStringInputStream extends StringInputStream implements PushBackInputStreamInterface
{
    /**
     * StringInputStream constructor.
     *
     * @param $source
     */
    public function __construct($source)
    {
        parent::__construct($source);
    }

    /**
     * Pushes back a character to stream
     *
     * @param xStringBuffer $buffer
     */
    public function unread(xStringBuffer $buffer): void
    {
        foreach($buffer as $c){
            $this->getSource()->prepend($c);
        }
    }
}