<?php
declare(strict_types=1);

namespace stk2k\iostream;

use stk2k\iostream\exception\IOException;

interface BaseStreamInterface
{

    /**
     * Returns if this stream is closed
     *
     * @return bool
     *
     * @throws IOException
     */
    public function isClosed() : bool;

    /**
     * Close the stream if it is not closed. If this function is called after calling close, second call will do
     * nothing to the stream.
     *
     * @return void
     *
     * @throws IOException
     */
    public function close() : void;

}