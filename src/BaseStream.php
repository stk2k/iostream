<?php
declare(strict_types=1);

namespace stk2k\iostream;

class BaseStream implements BaseStreamInterface
{
    /** @var bool  */
    private $closed = false;

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     *
     */
    public function close(): void
    {
        if (!$this->closed){
            $this->closed = true;
        }
    }
}