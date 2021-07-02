<?php
declare(strict_types=1);

namespace stk2k\iostream\test\string;

use Exception;

use PHPUnit\Framework\TestCase;
use stk2k\iostream\constants\SeekOrigin;
use stk2k\iostream\exception\IOException;
use stk2k\iostream\string\StringInputStream;
use stk2k\xstring\xStringBuffer;
use function stk2k\xstring\globals\s;

final class StringInputStreamTest extends TestCase
{
    public function testConstruct()
    {
        try {
            $this->assertEquals('Test', (new StringInputStream('Test'))->readLine());

            $this->assertEquals('Test', (new StringInputStream(s('Test')))->readLine());

            $this->assertEquals('Test', (new StringInputStream(new xStringBuffer('Test')))->readLine());
        }
        catch (Exception $ex) {
            $this->fail($ex->getMessage());
        }
    }
    public function testForeach()
    {
        $sis = new StringInputStream('Hello');

        ob_start();
        foreach ($sis as $c){
            echo $c . '.';    // H.e.l.l.o.
        }
        $text = ob_get_clean();

        $this->assertEquals('H.e.l.l.o.', $text);
    }
    public function testTell()
    {
        $sis = new StringInputStream('Test');

        $this->assertEquals(0, $sis->tell());

        $sis->read(2);

        $this->assertEquals(2, $sis->tell());
    }
    public function testSeek()
    {
        $sis = new StringInputStream('Test');

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
        $sis = new StringInputStream('Test');

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
        $sis = new StringInputStream(s('PHP is a popular general-purpose scripting language that is especially suited to web development.'));

        $this->assertEquals(0, $sis->tell());

        $text = $sis->read(50);

        $this->assertEquals('PHP is a popular general-purpose scripting languag', $text);

        $text = $sis->read(50);

        $this->assertEquals('e that is especially suited to web development.', $text);
    }
    public function testReadLine()
    {
        $sis = new StringInputStream("Foo\nBar\nBaz");

        $this->assertEquals(0, $sis->tell());

        $this->assertEquals("Foo", $sis->readLine());
        $this->assertEquals('Bar', $sis->readLine());
        $this->assertEquals('Baz', $sis->readLine());
    }
    public function testReadLines()
    {
        $sis = new StringInputStream("Foo\nBar\nBaz");

        $lines = $sis->readLines();

        $expected = ["Foo","Bar","Baz"];
        $this->assertEquals($expected, $lines);
    }
}