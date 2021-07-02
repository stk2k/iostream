<?php
declare(strict_types=1);

namespace stk2k\iostream\test\file;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use stk2k\filesystem\File;
use stk2k\iostream\constants\SeekOrigin;
use stk2k\iostream\exception\EOFException;
use stk2k\iostream\exception\FileInputStreamException;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\file\FileInputStream;

final class FileInputStreamTest extends TestCase
{
    public function testClose()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $this->assertEquals(false, $is->isClosed());

            $is->close();

            $this->assertEquals(true, $is->isClosed());
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testTell()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $this->assertEquals(0, $is->tell());

            $is->readLine();

            $this->assertEquals(98, $is->tell());
        }
        catch(FileInputStreamException|IOException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testSeek()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $this->assertEquals(0, $is->tell());

            $is->seek(-10, SeekOrigin::END);

            $this->assertEquals(198, $is->tell());

            $is->seek(0, SeekOrigin::END);

            $this->assertEquals(208, $is->tell());

            $is->seek(90, SeekOrigin::START);

            $this->assertEquals(90, $is->tell());

            $is->seek(25, SeekOrigin::CURRENT);

            $this->assertEquals(115, $is->tell());

            $is->seek(-115, SeekOrigin::CURRENT);

            $this->assertEquals(0, $is->tell());
        }
        catch(FileInputStreamException|IOException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testIsReadable()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $this->assertEquals(true, $is->isReadable());

            $is->close();

            $this->assertEquals(false, $is->isReadable());

            $is = new FileInputStream($file);

            $is->read(10000);

            $this->assertEquals(false, $is->isReadable());
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testRead()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $text = $is->read(50);

            $this->assertEquals('PHP is a popular general-purpose scripting languag', $text);

            $text = $is->read(50);

            $this->assertEquals("e that is especially suited to web development.\n\nF", $text);

            $text = $is->read(50);

            $this->assertEquals('ast, flexible and pragmatic, PHP powers everything', $text);

            $text = $is->read(50);

            $this->assertEquals(' from your blog to the most popular websites in th', $text);

            $text = $is->read(50);

            $this->assertEquals('e world.', $text);
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }

        try{
            $is->read(50);

            $this->fail();
        }
        catch(EOFException $ex){
            $this->assertTrue(true);
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testReadLine()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $text = $is->readLine();

            $this->assertEquals("PHP is a popular general-purpose scripting language that is especially suited to web development.\n", $text);

            $text = $is->readLine();

            $this->assertEquals("\n", $text);

            $text = $is->readLine();

            $this->assertEquals("Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.", $text);
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }

        try{
            $is->readLine();

            $this->fail();
        }
        catch(EOFException $ex){
            $this->assertTrue(true);
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
    public function testReadLines()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem('test/_files');

        $file = new File(vfsStream::url('myrootdir/a.txt'));

        try{
            $is = new FileInputStream($file);

            $lines = $is->readLines(2);

            $expected = [
                "PHP is a popular general-purpose scripting language that is especially suited to web development.\n",
                "\n",
            ];
            $this->assertEquals($expected, $lines);

            $is = new FileInputStream($file);

            $lines = $is->readLines();

            $expected = [
                "PHP is a popular general-purpose scripting language that is especially suited to web development.\n",
                "\n",
                "Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.",
            ];
            $this->assertEquals($expected, $lines);
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }

        try{
            $is->readLines();

            $this->fail();
        }
        catch(EOFException $ex){
            $this->assertTrue(true);
        }
        catch(FileInputStreamException $ex){
            $this->fail($ex->getMessage());
        }
    }
}