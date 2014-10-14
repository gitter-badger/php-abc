<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class RemoveWhitespaceCleaner
 *
 * @package SetBased\Html\Form\Cleaner
 */
class RemoveWhitespaceCleaner implements Cleaner
{

  /**
   * @var RemoveWhitespaceCleaner The singleton instance of this class.
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return RemoveWhitespaceCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns @a $theValue with all whitespace removed.
   *   *
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

    return trim( mb_ereg_replace( '[\ \t\n\r\0\x0B\xA0]+', '', $theValue, 'p' ) );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
