<?php
declare(strict_types=1);

namespace stk2k\iostream\file;

use stk2k\filesystem\Exception\FileOutputException;
use stk2k\filesystem\Exception\FileWriterException;
use stk2k\filesystem\File;
use stk2k\filesystem\FileWriter;
use stk2k\iostream\exception\FileOutputStreamException;
use stk2k\iostream\OutputStreamInterface;

final class FileOutputStream implements OutputStreamInterface
{
    /** @var FileWriter */
    private $writer;

    /**
     * FileOutputStream constructor.
     *
     * @param File $file
     * @param int $flag
     *
     * @throws FileOutputStreamException
     */
    public function __construct(File $file, int $flag = File::FILE_WRITE_TRUNCATE)
    {
        try{
            $this->writer = $file->openForWrite($flag);
        }
        catch(FileOutputException $ex){
            throw new FileOutputStreamException('Failed to open file for write: ' . $file, $ex);
        }
    }

    /**
     * Returns if this stream is closed
     *
     * @return bool
     */
    public function isClosed() : bool
    {
        return $this->writer->isClosed();
    }

    /**
     * Close the stream if it is not closed. If this function is called after calling close, second call will do
     * nothing to the stream.
     *
     * @return void
     */
    public function close() : void
    {
        $this->writer->close();
    }

    /**
     * Flush output stream
     *
     * @return void
     *
     * @throws FileOutputStreamException
     */
    public function flush() : void
    {
        try{
            $this->writer->flush();
        }
        catch(FileWriterException $ex){
            throw new FileOutputStreamException('Failed to flush file: ' . $this->writer->getFile(), $ex);
        }
    }

    /**
     * Returns if this stream can be written
     *
     * @return bool
     */
    public function isWritable() : bool
    {
        return !$this->isClosed();
    }

    /**
     * Write a line into steam.
     *
     * @param string $str
     *
     * @return void
     *
     * @throws FileOutputStreamException
     */
    public function write(string $str) : void
    {
        try{
            $this->writer->write($str);
        }
        catch(FileWriterException $ex){
            throw new FileOutputStreamException('Failed to write file: ' . $this->writer->getFile(), $ex);
        }
    }

    /**
     * Write a line into steam.
     *
     * @param string $line
     *
     * @return void
     *
     * @throws FileOutputStreamException
     */
    public function writeLine(string $line) : void
    {
        try{
            $this->writer->write($line . PHP_EOL);
        }
        catch(FileWriterException $ex){
            throw new FileOutputStreamException('Failed to write file: ' . $this->writer->getFile(), $ex);
        }
    }

    /**
     * Write multiple lines into steam.
     *
     * @param array $lines
     *
     * @return void
     *
     * @throws FileOutputStreamException
     */
    public function writeLines(array $lines) : void
    {
        try{
            foreach($lines as $line){
                $this->writer->write($line . PHP_EOL);
            }
        }
        catch(FileWriterException $ex){
            throw new FileOutputStreamException('Failed to write file: ' . $this->writer->getFile(), $ex);
        }
    }
}