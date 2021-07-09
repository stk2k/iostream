<?php
declare(strict_types=1);

namespace stk2k\iostream;

use stk2k\iostream\exception\IOException;

interface OutputStreamInterface extends BaseStreamInterface
{
    /**
     * Flush output stream
     *
     * @return void
     *
     * @throws IOException
     */
    public function flush() : void;

    /**
     * Returns if this stream can be written
     *
     * @return bool
     *
     * @throws IOException
     */
    public function isWritable() : bool;

    /**
     * Write a string into steam.
     *
     * @param string $str
     *
     * @return void
     *
     * @throws IOException
     */
    public function write(string $str) : void;

    /**
     * Write a line into steam.
     *
     * @param string $line
     *
     * @return void
     *
     * @throws IOException
     */
    public function writeLine(string $line) : void;

    /**
     * Write multiple lines into steam.
     *
     * @param array $lines
     *
     * @return void
     *
     * @throws IOException
     */
    public function writeLines(array $lines) : void;
}