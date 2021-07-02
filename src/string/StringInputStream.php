<?php
declare(strict_types=1);

namespace stk2k\iostream\string;

use InvalidArgumentException;
use Generator;

use stk2k\iostream\BaseStream;
use stk2k\iostream\constants\SeekOrigin;
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

    /**
     * StringInputStream constructor.
     *
     * @param $source
     */
    public function __construct($source)
    {
        if(is_string($source)){
            $source = new xString($source);
        }
        if (!($source instanceof xString) && !($source instanceof xStringBuffer)){
            throw new InvalidArgumentException('Source must be instance of xString or xStringBuffer, but you passed: ' . get_class($source));
        }
        $this->source = $source instanceof xString ? new xStringBuffer($source) : $source;
        $this->length = $this->source->length();
    }

    /**
     * @return Generator
     */
    public function getIterator(): Generator
    {
        foreach($this->source->substring($this->pos) as $c){
            yield $c;
        }
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
     */
    public function read(int $length = null): ?string
    {
        if ($this->pos >= $this->length){
            return null;
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
     */
    public function readLine(int $length = null) : ?string
    {
        if ($this->pos >= $this->length){
            return null;
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
     */
    public function readLines(int $lines = -1) : ?array
    {
        if ($this->pos >= $this->length){
            return null;
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