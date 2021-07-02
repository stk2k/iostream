<?php
declare(strict_types=1);

namespace stk2k\iostream;

use stk2k\iostream\exception\IOException;
use stk2k\xstring\xStringArray;
use stk2k\xstring\xString;

interface PipeInterface
{
    /**
     * Attach input/output stream
     *
     * @param InputStreamInterface $is
     * @param OutputStreamInterface $os
     *
     * @return $this
     */
    public function attach(InputStreamInterface $is, OutputStreamInterface $os) : self;

    /**
     * Attach input stream
     *
     * @param InputStreamInterface $is
     *
     * @return $this
     */
    public function attachInput(InputStreamInterface $is) : self;

    /**
     * Attach output stream
     *
     * @param OutputStreamInterface $os
     *
     * @return $this
     */
    public function attachOutput(OutputStreamInterface $os) : self;

    /**
     * Returns attached input stream
     *
     * @return InputStreamInterface|null
     */
    public function getInputStream() : ?InputStreamInterface;

    /**
     * Returns attached output stream
     *
     * @return OutputStreamInterface|null
     */
    public function getOutputStream() : ?OutputStreamInterface;

    /**
     * Detach input/output stream
     *
     * @return self
     *
     * @throws IOException
     */
    public function detach() : self;

    /**
     * Detach input stream
     *
     * @return self
     *
     * @throws IOException
     */
    public function detachInput() : self;

    /**
     * Detach output stream
     *
     * @return self
     *
     * @throws IOException
     */
    public function detachOutput() : self;

    /**
     * Reads a character from input steam and writes it into output stream.
     *
     * @return Char
     *
     * @throws IOException
     */
    public function processChar() : Char;

    /**
     * Reads multiple characters from input steam and writes it into output stream. It returns count of characters
     * succeeded to process
     *
     * @param int $chars
     *
     * @return xString
     *
     * @throws IOException
     */
    public function processChars(int $chars) : xString;

    /**
     * Reads a line from input steam and writes it into output stream.
     *
     * @return xString
     *
     * @throws IOException
     */
    public function processLine() : xString;

    /**
     * Reads multiple line from input steam and writes them into output stream. It returns count of lines
     * succeeded to process
     *
     * @param int $lines
     *
     * @return xStringArray
     *
     * @throws IOException
     */
    public function processLines(int $lines) : xStringArray;

}