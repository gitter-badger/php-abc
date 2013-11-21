<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class TidyCleaner
 * @package SetBased\Html\Form\Cleaner
 */
class TidyCleaner implements Cleaner
{
  /**
   * @var TidyCleaner The singleton instance of this class.
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
   * Returns @a $theValue as a HTML snippet cleaned by tidy.
   *
   * @param $theValue string
   *
   * @return string
   */
  public function clean( $theValue )
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean( $theValue );

    if ($value==='' || $value===null || $value===false)
    {
      return '';
    }

    $tidy_config = array('clean'          => false,
                         'output-xhtml'   => true,
                         'show-body-only' => true,
                         'wrap'           => 100);

    $tidy = new \tidy;

    $tidy->parseString( $value, $tidy_config, 'utf8' );
    $tidy->cleanRepair();
    $value = trim( tidy_get_output( $tidy ) );

    if (preg_match( '/^(([\ \r\n\t])|(<p>)|(<\/p>)|(&nbsp;))*$/', $value )==1)
    {
      $value = '';
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
