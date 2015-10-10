<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Formatter;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Formatter for formatting dates.
 */
class DateFormatter implements Formatter
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The format specifier, see <http://www.php.net/manual/function.date.php>.
   */
  private $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theFormat The date format, see <http://www.php.net/manual/function.date.php>.
   */
  public function __construct($theFormat)
  {
    $this->myFormat = $theFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If the machine value is a valid date returns the date formatted according the format specifier. Otherwise,
   * returns the machine value unchanged.
   *
   * @param string $theValue The machine value.
   *
   * @return string
   */
  public function format($theValue)
  {
    $parts = explode('-', $theValue);

    $valid = (count($parts)==3 && checkdate($parts[1], $parts[2], $parts[0]));

    if ($valid)
    {
      $date = new \DateTime($theValue);

      return $date->format($this->myFormat);
    }
    else
    {
      return $theValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
