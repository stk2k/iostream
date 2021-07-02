<?php
declare(strict_types=1);

namespace stk2k\iostream\exception;

use Throwable;

class EOFException extends FileInputStreamException
{
    /**
     * construct
     *
     * @param string $message
     * @param Throwable|null $prev
     */
    public function __construct(string $message, Throwable $prev = null){
        parent::__construct($message, $prev);
    }
}