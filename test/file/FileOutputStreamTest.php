<?php
declare(strict_types=1);

namespace stk2k\iostream\test\file;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use stk2k\filesystem\File;
use stk2k\iostream\exception\FileInputStreamException;
use stk2k\iostream\exception\FileOutputStreamException;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\file\FileInputStream;
use stk2k\iostream\file\FileOutputStream;
use stk2k\xstring\xs;

final class FileOutputStreamTest extends TestCase
{
    public function testClose()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $os = new FileOutputStream($file);

            $this->assertEquals(false, $os->isClosed());

            $os->close();

            $this->assertEquals(true, $os->isClosed());
        }
        catch(FileOutputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testIsWritable()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $os = new FileOutputStream($file);

            $this->assertEquals(true, $os->isWritable());
        }
        catch(FileOutputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testWrite()
    {
        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem('test/_files');

            $os = new FileOutputStream($file);

            $os->write("test");

            $is = new FileInputStream($file);

            $this->assertEquals('test', $is->read(100));

            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem('test/_files');

            $os = new FileOutputStream($file, File::FILE_WRITE_APPEND);

            $os->write("test");

            $is = new FileInputStream($file);

            $this->assertEquals(file_get_contents('test/_files/a.txt') . 'test', $is->read(999));
        }
        catch(FileOutputStreamException|FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testWriteLine()
    {
        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem('test/_files');

            $os = new FileOutputStream($file);

            $os->write("test");

            $is = new FileInputStream($file);

            $this->assertEquals('test', $is->read(100));

            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem('test/_files');

            $os = new FileOutputStream($file, File::FILE_WRITE_APPEND);

            $os->write("test");

            $is = new FileInputStream($file);

            $this->assertEquals(file_get_contents('test/_files/a.txt') . 'test', $is->read(999));
        }
        catch(FileOutputStreamException|FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }

    public function testWriteLines()
    {
        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem('test/_files');

            $os = new FileOutputStream($file);

            $os->writeLines(["Foo", "Bar", "Baz"]);

            $is = new FileInputStream($file);

            $this->assertEquals('Foo' . PHP_EOL . 'Bar' . PHP_EOL . 'Baz' . PHP_EOL, $is->read(100));

            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem('test/_files');

            $os = new FileOutputStream($file, File::FILE_WRITE_APPEND);

            $os->writeLines(["Foo", "Bar", "Baz"]);

            $is = new FileInputStream($file);

            $expected = file_get_contents('test/_files/a.txt') . 'Foo' . PHP_EOL . 'Bar' . PHP_EOL . 'Baz' . PHP_EOL;
            $this->assertEquals($expected, $is->read(999));
        }
        catch(FileOutputStreamException|FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
}