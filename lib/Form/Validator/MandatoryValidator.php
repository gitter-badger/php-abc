<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validates if a form control has a value. Can be applied on any form control object.
 */
class MandatoryValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the form control has a value.
   *
   * Note:
   * * Empty values are considered invalid.
   * * If the form control is a complex form control all child form control must have a value.
   *
   * @param Control $theFormControl The form control.
   *
   * @return bool
   */
  public function validate($theFormControl)
  {
    $value = $theFormControl->getSubmittedValue();

    if ($value==='' || $value===null || $value===false)
    {
      return false;
    }

    if (is_array($value))
    {
      return $this->validateArray($value);
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates recursively if one of the leaves of @a $theArray has a non-empty value.
   *
   * @param array $theArray
   *
   * @return bool
   */
  private function validateArray($theArray)
  {
    foreach ($theArray as $element)
    {
      if (is_scalar($element))
      {
        if ($element!==null && $element!==false && $element!=='')
        {
          return true;
        }
      }
      else
      {
        $tmp = $this->validateArray($element);
        if ($tmp===true)
        {
          return true;
        }
      }
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
