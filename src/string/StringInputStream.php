<?php
declare(strict_types=1);

namespace stk2k\iostream\string;

use stk2k\iostream\BaseStream;
use stk2k\iostream\constants\SeekOrigin;
use stk2k\iostream\exception\EOFException;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\InputStreamInterface;
use stk2k\xstring\xStringBuffer;
use stk2k\xstring\xString;

class StringInputStream extends BaseStream implements InputStreamInterface
{
    /** @var xStringBuffer */
    private $source;

    /** @var int */
    private $pos = 0;

    /** @var int */
    private $length;

    public function __construct(xString $source, int $length = null, string $fill = ' ')
    {
        $this->source = new xStringBuffer($source, $length, $fill);
        $this->length = $this->source->length();
    }

    /**
     * Returns if this stream supports random acccess
     *
     * @return bool
     */
    public function seekable() : bool
    {
        return true;
    }

    /**
     * Returns position of current reading
     *
     * @return int
     */
    public function tell() : int
    {
        return $this->pos;
    }

    /**
     * Returns if this stream supports random acccess
     *
     * @param int $offset
     * @param string $seek_origin
     *
     * @return void
     *
     * @throws IOException
     */
    public function seek(int $offset, string $seek_origin) : void
    {
        switch($seek_origin)
        {
            case SeekOrigin::START:
                if ($offset < 0){
                    throw new IOException('Cannot seek before start position: ' . $offset);
                }
                if ($offset > $this->length - 1){
                    throw new IOException('Cannot seek after end position: ' . $offset);
                }
                $this->pos = $offset;
                break;

            case SeekOrigin::END:
                if ($offset > 0){
                    throw new IOException('Cannot seek after end position: ' . $offset);
                }
                if ($offset < -($this->length - 1)){
                    throw new IOException('Cannot seek before start position: ' . $offset);
                }
                $this->pos = $this->length - 1 + $offset;
                break;

            case SeekOrigin::CURRENT:
                if ($this->pos + $offset > $this->length - 1){
                    throw new IOException('Cannot seek after end position: ' . $offset);
                }
                if ($this->pos + $offset < 0){
                    throw new IOException('Cannot seek before start position: ' . $offset);
                }
                $this->pos += $offset;
                break;
        }
    }

    /**
     * Returns if this stream can be read
     *
     * @return bool
     */
    public function isReadable() : bool
    {
        return $this->pos < $this->length;
    }

    /**
     * @param int|null $length
     *
     * @return string|null
     * @throws EOFException
     */
    public function read(int $length = null): ?string
    {
        if ($this->pos >= $this->length){
            throw new EOFException('String input stream reached to EOF: ' . $this->source->toString());
        }
        if ($length > 0){
            $str = $this->source->substring($this->pos, $length)->value();
            $this->pos += $length;
        }
        else{
            $str = $this->source->substring($this->pos)->value();
            $this->pos = $this->length - 1;
        }
        return $str;
    }

    /**
     * Reads a line from steam. If the stream reached to the end, it returns EOF character('')
     *
     * @param int|null $length
     *
     * @return string|null
     * @throws EOFException
     */
    public function readLine(int $length = null) : ?string
    {
        if ($this->pos >= $this->length){
            throw new EOFException('String input stream reached to EOF: ' . $this->source->toString());
        }
        $str = '';
        $read = 0;
        foreach($this->source->substring($this->pos) as $c)
        {
            $this->pos ++;
            $read ++;
            if ($length > 0 && $read >= $length){
                return $str;
            }
            if ($c === "\n" || $c === "\r"){
                return $str;
            }
            else{
                $str .= $c;
            }
        }
        return $str;
    }

    /**
     * Reads multiple lines from steam. If the stream reached to the end, it returns EOF character('')
     * If the lines parameter is specified and its value is negative, this function tries to read all
     * lines from stream.
     *
     * @param int $lines
     *
     * @return array
     * @throws EOFException
     */
    public function readLines(int $lines = -1) : array
    {
        if ($this->pos >= $this->length){
            throw new EOFException('String input stream reached to EOF: ' . $this->source->toString());
        }
        $lines = [];
        $str = '';
        $read = 0;
        foreach($this->source->substring($this->pos) as $c)
        {
            $this->pos ++;
            $read ++;
            if ($c === "\n" || $c === "\r"){
                $lines[] = $str;
                $str = '';
            }
            else{
                $str .= $c;
            }
        }
        if (!empty($str)){
            $lines[] = $str;
        }
        return $lines;
    }

}