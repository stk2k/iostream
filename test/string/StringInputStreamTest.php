<?php
declare(strict_types=1);

namespace stk2k\iostream\test\string;

use PHPUnit\Framework\TestCase;
use stk2k\iostream\constants\SeekOrigin;
use stk2k\iostream\exception\EOFException;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\string\StringInputStream;
use function stk2k\xstring\globals\s;

final class StringInputStreamTest extends TestCase
{
    public function testTell()
    {
        $sis = new StringInputStream(s('Test'));

        $this->assertEquals(0, $sis->tell());

        $sis->read(2);

        $this->assertEquals(2, $sis->tell());
    }
    public function testSeek()
    {
        $sis = new StringInputStream(s('Test'));

        try {
            $this->assertEquals(0, $sis->tell());

            $sis->seek(2, SeekOrigin::START);

            $this->assertEquals(2, $sis->tell());

            $sis->seek(1, SeekOrigin::CURRENT);

            $this->assertEquals(3, $sis->tell());

            $sis->seek(0, SeekOrigin::END);

            $this->assertEquals(3, $sis->tell());

            $sis->seek(-3, SeekOrigin::END);

            $this->assertEquals(0, $sis->tell());
        }
        catch (IOException $ex) {
            $this->fail($ex->getMessage());
        }
    }
    public function testIsReadable()
    {
        $sis = new StringInputStream(s('Test'));

        try {
            $this->assertEquals(0, $sis->tell());

            $sis->seek(2, SeekOrigin::START);

            $this->assertEquals(true, $sis->isReadable());

            $sis->seek(1, SeekOrigin::CURRENT);

            $this->assertEquals(true, $sis->isReadable());

            $sis->read(1);

            $this->assertEquals(false, $sis->isReadable());
        }
        catch (IOException $ex) {
            $this->fail($ex->getMessage());
        }
    }
    public function testRead()
    {
        try{
            $sis = new StringInputStream(s('PHP is a popular general-purpose scripting language that is especially suited to web development.'));

            $this->assertEquals(0, $sis->tell());

            $text = $sis->read(50);

            $this->assertEquals('PHP is a popular general-purpose scripting languag', $text);

            $text = $sis->read(50);

            $this->assertEquals('e that is especially suited to web development.', $text);
        }
        catch (EOFException $ex) {
            $this->fail($ex->getMessage());
        }
    }
    public function testReadLine()
    {
        $sis = new StringInputStream(s("Foo\nBar\nBaz"));

        $this->assertEquals(0, $sis->tell());

        try{
            $this->assertEquals("Foo", $sis->readLine());
            $this->assertEquals('Bar', $sis->readLine());
            $this->assertEquals('Baz', $sis->readLine());
        }
        catch (EOFException $ex) {
            $this->fail($ex->getMessage());
        }

        try{
            $sis->readLine();
            $this->fail();
        }
        catch (EOFException $ex) {
            $this->assertTrue(true);
        }
    }
    public function testReadLines()
    {
        try{
            $sis = new StringInputStream(s("Foo\nBar\nBaz"));

            $lines = $sis->readLines();

            $expected = ["Foo","Bar","Baz"];
            $this->assertEquals($expected, $lines);
        }
        catch (EOFException $ex) {
            $this->fail($ex->getMessage());
        }

        try{
            $sis->readLine();
            $this->fail();
        }
        catch (EOFException $ex) {
            $this->assertTrue(true);
        }
    }
}