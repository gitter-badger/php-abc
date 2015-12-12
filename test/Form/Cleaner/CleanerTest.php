<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Cleaner\Cleaner;

/**
 * Class CleanerTest
 */
abstract class CleanerTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  protected $myEmptyValues = ['', false, null, ' ', '  ', "\n", "\n \n", "\n \t"];

  protected $myZeroValues = ['0', ' 0 ', "\t 0 \n"];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a cleaner.
   *
   * @return Cleaner
   */
  abstract function makeCleaner();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * All cleaners must return null when cleaning empty (i.e. only whitespace) values.
   */
  public function testEmptyValues()
  {
    $cleaner = $this->makeCleaner();

    foreach ($this->myEmptyValues as $value)
    {
      $cleaned = $cleaner->clean($value);

      $this->assertNull($cleaned, sprintf("Cleaning '%s' must return null.", addslashes($value)));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Most cleaners must return '0' when cleaning '0' values.
   */
  public function testZeroValues()
  {
    $cleaner = $this->makeCleaner();

    foreach ($this->myZeroValues as $value)
    {
      $cleaned = $cleaner->clean($value);

      $this->assertEquals('0', $cleaned, sprintf("Cleaning '%s' must return '0'.", addslashes($value)));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
