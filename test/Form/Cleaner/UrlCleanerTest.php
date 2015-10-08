<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Cleaner\UrlCleaner;

//----------------------------------------------------------------------------------------------------------------------
class UrlCleanerTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testFtp()
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'ftp://ftp.setbased.nl';
    $value = $cleaner->clean($raw);
    $this->assertEquals('ftp://ftp.setbased.nl/', $value);

    $raw   = '  ftp://user:password@ftp.setbased.nl';
    $value = $cleaner->clean($raw);
    $this->assertEquals('ftp://user:password@ftp.setbased.nl/', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testHttp()
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'www.setbased.nl';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/', $value);

    $raw   = '  www.setbased.nl  ';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/', $value);

    $raw   = 'www.setbased.nl/';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/', $value);

    $raw   = 'www.setbased.nl/index.php';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/index.php', $value);

    $raw   = 'www.setbased.nl#here';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/#here', $value);

    $raw   = 'www.setbased.nl?query';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/?query', $value);

    $raw   = 'http://www.setbased.nl?query';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/?query', $value);

    $raw   = 'www.setbased.nl?a=1;b=2';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/?a=1;b=2', $value);

    $raw   = 'www.setbased.nl/test/index.php?a=1;b=2';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/test/index.php?a=1;b=2', $value);

    $raw   = 'www.setbased.nl/test/test';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/test/test', $value);

    $raw   = 'www.setbased.nl/test/test/index.php?a=1;b=2#here';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/test/test/index.php?a=1;b=2#here', $value);

    $raw   = 'http://www.setbased.nl/test/test/index.php?a=1;b=2#here';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/test/test/index.php?a=1;b=2#here', $value);

    $raw   = 'http://www.setbased.nl';
    $value = $cleaner->clean($raw);
    $this->assertEquals('http://www.setbased.nl/', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testMailTo()
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'mailto:info@setbased.nl ';
    $value = $cleaner->clean($raw);
    $this->assertEquals('mailto:info@setbased.nl', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

