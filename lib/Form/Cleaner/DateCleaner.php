<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
  /**
   * Class DateCleaner
   *
   * @package SetBased\Html\Form\Cleaner
   */
/**
 * Class DateCleaner
 *
 * @package SetBased\Html\Form\Cleaner
 */
class DateCleaner implements Cleaner
{
  /**
   * @var string The alternative separators in the format of this validator.
   */
  protected $myAlternativeSeparators;

  /**
   * @var string The expected format of the date.
   */
  protected $myFormat;

  /**
   * @var string The expected separator in the format of this validator.
   */
  protected $mySeparator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param      $theFormat
   * @param null $theSeparator
   * @param null $theAlternativeSeparators
   */
  public function __construct( $theFormat, $theSeparator = null, $theAlternativeSeparators = null )
  {
    $this->myFormat                = $theFormat;
    $this->mySeparator             = $theSeparator;
    $this->myAlternativeSeparators = $theAlternativeSeparators;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theValue
   *
   * @return string
   */
  public function clean( $theValue )
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean( $theValue );

    // If the value is empty return immediately.
    if ($value==='' || $value===null || $value===false)
    {
      return '';
    }

    // First validate against ISO 8601.
    $parts = explode( '-', $theValue );
    $valid = (sizeof( $parts )==3 && checkdate( $parts[1], $parts[2], $parts[0] ));
    if ($valid)
    {
      $date = new \DateTime($theValue);

      return $date->format( 'Y-m-d' );
    }

    // Replace alternative separators with the expected separator.
    if ($this->mySeparator && $this->myAlternativeSeparators)
    {
      $value = strtr( $value,
                       $this->myAlternativeSeparators,
                       str_repeat( $this->mySeparator[0], strlen( $this->myAlternativeSeparators ) ) );
    }

    // Validate against $myFormat.
    $date = \DateTime::createFromFormat( $this->myFormat, $value );
    if ($date)
    {
      // Note: String '2000-02-30' will transformed to date '2000-03-01' with a warning. We consider this as an
      // invalid date.
      $tmp = $date->getLastErrors();
      if ($tmp['warning_count']==0) return $date->format( 'Y-m-d' );
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
