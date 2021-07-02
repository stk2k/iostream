<?php
declare(strict_types=1);

namespace stk2k\iostream\pipe;

use stk2k\iostream\Char;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\InputStreamInterface;
use stk2k\iostream\OutputStreamInterface;
use stk2k\iostream\PipeInterface;
use stk2k\xstring\xStringArray;
use stk2k\xstring\xString;
use stk2k\xstring\xs;

class BufferlessPipe implements PipeInterface
{
    /** @var InputStreamInterface */
    private $is;

    /** @var OutputStreamInterface */
    private $os;

    /**
     * BufferlessPipe constructor.
     *
     * @param InputStreamInterface|null $is
     * @param OutputStreamInterface|null $os
     */
    public function __construct(InputStreamInterface $is = null, OutputStreamInterface $os = null)
    {
        $this->is = $is;
        $this->os = $os;
    }

    /**
     * @param InputStreamInterface $is
     * @param OutputStreamInterface $os
     *
     * @return PipeInterface
     */
    public function attach(InputStreamInterface $is, OutputStreamInterface $os): PipeInterface
    {
        $this->is = $is;
        $this->os = $os;
        return $this;
    }

    /**
     * @param InputStreamInterface $is
     *
     * @return PipeInterface
     */
    public function attachInput(InputStreamInterface $is): PipeInterface
    {
        $this->is = $is;
        return $this;
    }

    /**
     * @param OutputStreamInterface $os
     *
     * @return PipeInterface
     */
    public function attachOutput(OutputStreamInterface $os): PipeInterface
    {
        $this->os = $os;
        return $this;
    }

    /**
     * @return InputStreamInterface|null
     */
    public function getInputStream(): ?InputStreamInterface
    {
        return $this->is;
    }

    /**
     * @return OutputStreamInterface|null
     */
    public function getOutputStream(): ?OutputStreamInterface
    {
        return $this->os;
    }

    /**
     * @return PipeInterface
     */
    public function detach(): PipeInterface
    {
        $this->is = null;
        $this->os = null;
        return $this;
    }

    /**
     * @return PipeInterface
     */
    public function detachInput(): PipeInterface
    {
        $this->is = null;
        return $this;
    }

    /**
     * @return PipeInterface
     */
    public function detachOutput(): PipeInterface
    {
        $this->os = null;
        return $this;
    }

    /**
     * @return Char
     * @throws IOException
     */
    public function processChar(): Char
    {
        $c = $this->is->readChar();
        $this->os->writeChar($c);
        return $c;
    }

    /**
     * @param int $chars
     *
     * @return xString
     * @throws IOException
     */
    public function processChars(int $chars): xString
    {
        $str = null;
        for($i=0; $i<$chars; $i++){
            $c = $this->is->readChar();
            if (!$c){
                $str = $c->toString();
            }
            else{
                $str->concat($c->toString());
            }
        }
        return $str ?? xs::newString('');
    }

    /**
     * @return xString
     * @throws IOException
     */
    public function processLine(): xString
    {
        $line = $this->is->readLine();
        $this->os->writeLine($line);
        return $line;
    }

    /**
     * @param int $lines
     *
     * @return xStringArray
     * @throws IOException
     */
    public function processLines(int $lines): xStringArray
    {
        $lines = new xStringArray();
        for($i=0; $i<$lines; $i++){
            $line = $this->is->readLine();
            $lines->append($line->value());
        }
        return $lines;
    }

}