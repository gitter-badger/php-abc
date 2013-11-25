<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class IntegerValidator
 * Validates if the value of a form control (derived from FormControl) is a valid email address.
 * Can only be applied on form controls which values are strings.
 *
 * @package SetBased\Html\Form
 */
class IntegerValidator implements ControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The upper bound of the range of valid (integer) values.
   */
  private $myMaxValue;

  /**
   * The lower bound of the range of valid (integer) values.
   */
  private $myMinValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct( $theMinValue = null, $theMaxValue = PHP_INT_MAX )
  {
    $this->myMinValue = (isset($theMinValue)) ? $theMinValue : -PHP_INT_MAX;
    $this->myMaxValue = $theMaxValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Returns @c true if
   *  * @a $theFormControl has no value.
   *  * The value of @a $theFormControl is an integer.
   *  Otherwise returns @c false.
   *
   * @param Control\Control $theFormControl
   *
   * @return bool
   */
  public function validate( $theFormControl )
  {
    $options = array('options' => array('min_range' => $this->myMinValue,
                                        'max_range' => $this->myMaxValue));

    $value = $theFormControl->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null || $value===false)
    {
      return true;
    }

    // Objects and arrays are not an integer.
    if (!is_scalar( $value ))
    {
      return false;
    }

    // Filter valid integer values with valid range.
    $integer = filter_var( $value, FILTER_VALIDATE_INT, $options );

    // If the actual value and the filtered value are not equal the value is not an integer.
    if ((string)$integer!==(string)$value)
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
