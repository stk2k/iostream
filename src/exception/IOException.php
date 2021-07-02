<?php
declare(strict_types=1);

namespace stk2k\iostream\exception;

use Throwable;
use Exception;

class IOException extends Exception
{
    /**
     * construct
     *
     * @param string $message
     * @param Throwable|null $prev
     */
    public function __construct(string $message, Throwable $prev = null){
        parent::__construct($message, 0, $prev);
    }
}