<?php
declare(strict_types=1);

namespace stk2k\iostream\file;

use Generator;

use stk2k\filesystem\Exception\FileInputException;
use stk2k\filesystem\Exception\FileOperatorException;
use stk2k\filesystem\Exception\FileReaderException;
use stk2k\filesystem\File;
use stk2k\filesystem\FileReader;
use stk2k\iostream\constants\SeekOrigin;
use stk2k\iostream\exception\FileInputStreamException;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\InputStreamInterface;

final class FileInputStream implements InputStreamInterface
{
    /** @var File */
    private $file;

    /** @var FileReader */
    private $reader;

    /**
     * FileInputStream constructor.
     *
     * @param File $file
     *
     * @throws FileInputStreamException
     */
    public function __construct(File $file)
    {
        try{
            $this->file = $file;
            $this->reader = $file->openForRead();
        }
        catch(FileInputException $ex){
            throw new FileInputStreamException('Failed to open file for read: ' . $file, $ex);
        }
    }

    /**
     * @return Generator
     * @throws FileInputStreamException
     * @throws FileReaderException
     */
    public function getIterator(): Generator
    {
        try{
            $reader = $this->file->openForRead();

            while($c = $reader->read(1)){
                yield $c;
            }
        }
        catch(FileInputException $ex){
            throw new FileInputStreamException('Detected exception while reading file: ' . $this->file, $ex);
        }
    }

    /**
     * Returns if this stream is closed
     *
     * @return bool
     */
    public function isClosed() : bool
    {
        return $this->reader->isClosed();
    }

    /**
     * Close the stream if it is not closed. If this function is called after calling close, second call will do
     * nothing to the stream.
     *
     * @return void
     */
    public function close() : void
    {
        $this->reader->close();
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
     *
     * @throws IOException
     */
    public function tell() : int
    {
        try{
            return $this->reader->tell();
        }
        catch(FileOperatorException $ex){
            throw new FileInputStreamException('Failed to open file for read: ' . $this->reader->getFile(), $ex);
        }
    }

    /**
     * Seeks pointer
     *
     * @param int $offset
     * @param string $seek_origin
     *
     * @return void
     *
     * @throws FileInputStreamException
     */
    public function seek(int $offset, string $seek_origin) : void
    {
        try{
            switch($seek_origin)
            {
                case SeekOrigin::START:
                    $this->reader->seekToStart($offset);
                    break;

                case SeekOrigin::CURRENT:
                    $this->reader->seek($offset);
                    break;

                case SeekOrigin::END:
                    $this->reader->seekToEnd($offset);
                    break;
            }
        }
        catch(FileOperatorException $ex){
            throw new FileInputStreamException('Failed to seek file: ' . $this->reader->getFile(), $ex);
        }
    }

    /**
     * Returns if this stream can be read
     *
     * @return bool
     */
    public function isReadable() : bool
    {
        return !$this->reader->isClosed() && !$this->reader->isEOF();
    }

    /**
     * Reads a character from steam. If the stream reached to the end, it returns EOF character('')
     *
     * @param int|null $length
     *
     * @return string|null
     * @throws FileInputStreamException
     */
    public function read(int $length = null) : ?string
    {
        try{
            return $this->reader->read($length);
        }
        catch(FileReaderException $ex){
            throw new FileInputStreamException('Failed to read file: ' . $this->reader->getFile(), $ex);
        }
    }

    /**
     * Reads a line from steam. If the stream reached to the end, it returns EOF character('')
     *
     * @param int|null $length
     *
     * @return string|null
     * @throws FileInputStreamException
     */
    public function readLine(int $length = null) : ?string
    {
        try{
            $line = $this->reader->getLine($length);
            return $line ? rtrim($line, "\n\r") : $line;
        }
        catch(FileReaderException $ex){
            throw new FileInputStreamException('Failed to read line from file: ' . $this->reader->getFile(), $ex);
        }
    }

    /**
     * Reads multiple lines from steam. If the stream reached to the end, it returns EOF character('')
     * If the lines parameter is specified and its value is negative, this function tries to read all
     * lines from stream.
     *
     * @param int $lines
     *
     * @return array
     * @throws FileInputStreamException
     */
    public function readLines(int $lines = -1) : ?array
    {
        try{
            $ret = null;
            if ($lines > 0){
                for($i=0; $i<$lines; $i++){
                    $line = $this->reader->getLine();
                    if (!$line){
                        return $ret;
                    }
                    $ret[] = rtrim($line, "\n\r");
                }
            }
            else{
                while(!$this->reader->isEOF()){
                    $line = $this->reader->getLine();
                    $ret[] = rtrim($line, "\n\r");
                }
            }
            return $ret;
        }
        catch(FileReaderException $ex){
            throw new FileInputStreamException('Failed to read line from file: ' . $this->reader->getFile(), $ex);
        }
    }

}