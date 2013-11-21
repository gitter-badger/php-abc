<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class PruneWhitespaceCleaner
 * @package SetBased\Html\Form\Cleaner
 */
class PruneWhitespaceCleaner implements Cleaner
{
  /**
   * @var PruneWhitespaceCleaner The singleton instance of this class.
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return PruneWhitespaceCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns @a $theValue with leading and training whitespace removed. Intermediate whitespace and multiple
   * intermediate whitespace are replaces with a single space.
   *
   * @param $theValue string
   *
   * @return string
   */
  public function clean( $theValue )
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      return '';
    }

    return trim( mb_ereg_replace( '[\ \t\n\r\0\x0B\xA0]+', ' ', $theValue, 'p' ) );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
