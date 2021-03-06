<?php
//--------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Control\DateControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validator for dates.
 */
class DateValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is a valid date. Otherwise returns false.
   *
   * Note:
   * * Empty values are considered valid.
   *
   * @param DateControl $theFormControl
   *
   * @return bool
   */
  public function validate($theFormControl)
  {
    $value = $theFormControl->getSubmittedValue();

    // An empty value is valid.
    if ($value===null || $value===false || $value==='') return true;

    // Objects and arrays are not valid dates.
    if (!is_scalar($value)) throw new LogicException("%s is not a valid date.", gettype($value));

    // We assume that DateCleaner did a good job and date is in YYYY-MM-DD format.
    $parts = explode('-', $value);

    $valid = (count($parts)==3 && checkdate($parts[1], $parts[2], $parts[0]));
    if (!$valid)
    {
      // @todo babel
      $message = sprintf("'%s' is geen geldige datum.", $theFormControl->getSubmittedValue());
      $theFormControl->setErrorMessage($message);
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
