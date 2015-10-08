<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Cleaner\RemoveWhitespaceCleaner;

//----------------------------------------------------------------------------------------------------------------------
class RemoveWhitespaceCleanerTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testClean()
  {
    $raw     = "  Hello  \n\n  World!   ";
    $cleaner = RemoveWhitespaceCleaner::get();
    $value   = $cleaner->clean( $raw );

    $this->assertEquals( 'HelloWorld!', $value );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

