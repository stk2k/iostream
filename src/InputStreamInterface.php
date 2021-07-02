<?php
declare(strict_types=1);

namespace stk2k\iostream;

use IteratorAggregate;

use stk2k\iostream\exception\IOException;

interface InputStreamInterface extends BaseStreamInterface, IteratorAggregate
{
    /**
     * Returns if this stream supports random acccess
     *
     * @return bool
     *
     * @throws IOException
     */
    public function seekable() : bool;

    /**
     * Returns position of current reading
     *
     * @return int
     *
     * @throws IOException
     */
    public function tell() : int;

    /**
     * Seeks pointer
     *
     * @param int $offset
     * @param string $seek_origin
     *
     * @return void
     *
     * @throws IOException
     */
    public function seek(int $offset, string $seek_origin) : void;

    /**
     * Returns if this stream can be read
     *
     * @return bool
     *
     * @throws IOException
     */
    public function isReadable() : bool;

    /**
     * Reads a character from steam. If the stream reached to the end, it returns EOF character('')
     *
     * @param int|null $length
     *
     * @return string|null
     *
     * @throws IOException
     */
    public function read(int $length = null) : ?string;

    /**
     * Reads a line from steam. If the stream reached to the end, it returns EOF character('')
     *
     * @param int|null $length
     *
     * @return string|null
     *
     * @throws IOException
     */
    public function readLine(int $length = null) : ?string;

    /**
     * Reads multiple lines from steam. If the stream reached to the end, it returns EOF character('')
     * If the lines parameter is specified and its value is negative, this function tries to read all
     * lines from stream.
     *
     * @param int $lines
     *
     * @return string[]|null
     *
     * @throws IOException
     */
    public function readLines(int $lines = -1) : ?array;
}