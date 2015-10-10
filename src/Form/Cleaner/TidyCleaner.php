<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Helper\Html;

/**
 * Class TidyCleaner
 *
 * @package SetBased\Form\Form\Cleaner
 */
class TidyCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var TidyCleaner
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return PruneWhitespaceCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a HTML snippet cleaned by [Tidy](http://tidy.sourceforge.net/).
   *
   * @param string $theValue The submitted HTML snippet.
   *
   * @return string
   */
  public function clean($theValue)
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($theValue);

    if ($value==='' || $value===null || $value===false)
    {
      return '';
    }

    $tidy_config = ['clean'          => false,
                    'output-xhtml'   => true,
                    'show-body-only' => true,
                    'wrap'           => 100];

    $tidy = new \tidy;

    $tidy->parseString($value, $tidy_config, Html::$ourEncoding);
    $tidy->cleanRepair();
    $value = trim(tidy_get_output($tidy));

    // In some cases Tidy returns an empty paragraph only.
    if (preg_match('/^(([\ \r\n\t])|(<p>)|(<\/p>)|(&nbsp;))*$/', $value)==1)
    {
      $value = '';
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
