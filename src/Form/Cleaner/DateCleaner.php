<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Cleaner for cleaning and transforming dates to ISO 8601 machine format.
 */
class DateCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The alternative separators in the format of this validator.
   *
   * @var string
   */
  protected $myAlternativeSeparators;

  /**
   * The expected format of the date.
   *
   * @var string
   */
  protected $myFormat;

  /**
   * The expected separator in the format of this validator.
   *
   * @var string
   */
  protected $mySeparator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theFormat                The expected date format. See
   *                                         [DateTime::createFromFormat](http://php.net/manual/datetime.createfromformat.php)
   *                                         for the formatting options.
   * @param string $theSeparator             The separator (a single character) in the expected format.
   * @param string $theAlternativeSeparators Alternative separators (each character is an alternative separator).
   */
  public function __construct($theFormat, $theSeparator = null, $theAlternativeSeparators = null)
  {
    $this->myFormat                = $theFormat;
    $this->mySeparator             = $theSeparator;
    $this->myAlternativeSeparators = $theAlternativeSeparators;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans a submitted date and returns the date in ISO 8601 machine format if the date is a valid date. Otherwise
   * returns the original submitted value.
   *
   * @param string $theValue The submitted date.
   *
   * @return string
   */
  public function clean($theValue)
  {
    // First prune whitespace.
    $value = PruneWhitespaceCleaner::get()->clean($theValue);

    // If the value is empty return immediately.
    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    // First validate against ISO 8601.
    $parts = explode('-', $theValue);
    $valid = (count($parts)==3 && checkdate($parts[1], $parts[2], $parts[0]));
    if ($valid)
    {
      $date = new \DateTime($theValue);

      return $date->format('Y-m-d');
    }

    // Replace alternative separators with the expected separator.
    if ($this->mySeparator && $this->myAlternativeSeparators)
    {
      $value = strtr($value,
                     $this->myAlternativeSeparators,
                     str_repeat($this->mySeparator[0], strlen($this->myAlternativeSeparators)));
    }

    // Validate against $myFormat.
    $date = \DateTime::createFromFormat($this->myFormat, $value);
    if ($date)
    {
      // Note: String '2000-02-30' will transformed to date '2000-03-01' with a warning. We consider this as an
      // invalid date.
      $tmp = $date->getLastErrors();
      if ($tmp['warning_count']==0) return $date->format('Y-m-d');
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
