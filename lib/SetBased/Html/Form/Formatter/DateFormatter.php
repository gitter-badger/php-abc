<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Formatter;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class DateFormatter
 *
 * @package SetBased\Html\Form\Formatter
 */
class DateFormatter implements Formatter
{
  /**
   * @var string The format specifier, see http://www.php.net/manual/en/function.date-format.php.
   */
  private $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theFormat The date format, see http://www.php.net/manual/en/function.date-format.php.
   */
  public function __construct( $theFormat )
  {
    $this->myFormat = $theFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If @a $theValue is a valid date, the @a is formatted according the the format specifier of this formatter.
   * Otherwise, returns @theValue.
   *
   * @param string $theValue
   *
   * @return string
   */
  public function format( $theValue )
  {
    $parts = explode( '-', $theValue );

    $valid = (sizeof( $parts )==3 && checkdate( $parts[1], $parts[2], $parts[0] ));

    if ($valid)
    {
      $date = new \DateTime( $theValue );
      return $date->format( $this->myFormat );
    }
    else
    {
      return $theValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
