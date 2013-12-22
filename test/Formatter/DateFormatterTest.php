<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form\Formatter\DateFormatter;

//----------------------------------------------------------------------------------------------------------------------
class DateFormatterTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testInvalidDate1()
  {
    $text      = '1966-11-31';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format( $text );

    $this->assertEquals( $text, $value );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testInvalidDate2()
  {
    $text      = 'This not a date.';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format( $text );

    $this->assertEquals( $text, $value );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testValidDate()
  {
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format( '1966-04-10' );

    $this->assertEquals( '10-04-1966', $value );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

