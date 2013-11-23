<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class PruneWhitespaceCleaner
 * @package SetBased\Html\Form\Cleaner
 */
class TrimWhitespaceCleaner implements Cleaner
{
  /**
   * @var TrimWhitespaceCleaner The singleton instance of this class.
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
   * Returns @a $theValue with leading and training whitespace removed.
   *
   * @param string $theValue
   *
   * @return string
   */
  public function clean( $theValue )
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      return '';
    }

    return trim( $theValue, " \t\n\r\0\x0B\xA0" );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
