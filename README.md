Input and output stream library
=======================

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stk2k/iostream.svg?style=flat-square)](https://packagist.org/packages/stk2k/iostream)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/stk2k/iostream.svg?branch=master)](https://travis-ci.org/stk2k/iostream)
[![Coverage Status](https://coveralls.io/repos/github/stk2k/iostream/badge.svg?branch=master)](https://coveralls.io/github/stk2k/iostream?branch=master)
[![Code Climate](https://codeclimate.com/github/stk2k/iostream/badges/gpa.svg)](https://codeclimate.com/github/stk2k/iostream)
[![Total Downloads](https://img.shields.io/packagist/dt/stk2k/iostream.svg?style=flat-square)](https://packagist.org/packages/stk2k/iostream)

## Description

Input and output stream library

## Demo

### StringInputStream

```php
use stk2k\iostream\string\StringInputStream;

// foreach
$sis = new StringInputStream('Hello');
foreach ($sis as $c){
    echo $c . '.';    // H.e.l.l.o.
}

// read
$sis = new StringInputStream('Hello');
while($c = $sis->read(1)){
    echo $c . '.';    // H.e.l.l.o.
}

// read line
$sis = new StringInputStream("Foo\nBar\nBaz");
while($line = $sis->readLine()){
    echo $line . '.';    // Foo.Bar.Baz.
}

// read lines
$sis = new StringInputStream("Foo\nBar\nBaz");
$lines = $sis->readLines();
echo implode('.', $lines);    // Foo.Bar.Baz
```

### FileInputStream

```php
use stk2k\filesystem\File;
use stk2k\iostream\file\FileInputStream;

// foreach
$file = new File('test/_files/b.txt');
$fis = new FileInputStream($file);
foreach ($fis as $c){
    echo $c . '.';    // H.e.l.l.o.
}

// read
$file = new File('test/_files/b.txt');
$fis = new FileInputStream($file);
while($c = $fis->read(1)){
    echo $c . '.';    // H.e.l.l.o.
}

// read line
$file = new File('test/_files/c.txt');
$fis = new FileInputStream($file);
while($line = $fis->readLine()){
    echo $line . '.';    // Foo.Bar.Baz.
}

// read lines
$file = new File('test/_files/c.txt');
$fis = new FileInputStream($file);
$lines = $fis->readLines();
echo implode('.', $lines);    // Foo.Bar.Baz
```

## Requirement

PHP 7.1 or later

## Installing stk2k/iostream

The recommended way to install stk2k/iostream is through
[Composer](http://getcomposer.org).

```bash
composer require stk2k/iostream
```


## License
[MIT](https://github.com/stk2k/iostream/blob/master/LICENSE)

## Author

[stk2k](https://github.com/stk2k)

## Disclaimer

This software is no warranty.

We are not responsible for any results caused by the use of this software.

Please use the responsibility of the your self.


