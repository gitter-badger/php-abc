<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Validator;

use SetBased\Html\Form\Control\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class MandatoryValidator
 * Validates if a form control has a value.
 * Can be applied on any form control object.
 *
 * @package SetBased\Html\Form
 */
class MandatoryValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Returns @c true if
   *  * Each child form control has a value.
   *  Otherwise returns @c false.
   *
   * @param Control $theFormControl
   *
   * @return bool
   */
  public function validate( $theFormControl )
  {
    $value = $theFormControl->getSubmittedValue();

    if ($value==='' || $value===null || $value===false)
    {
      return false;
    }

    if (is_array( $value ))
    {
      return $this->validateArray( $value );
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
  private function validateArray( $theArray )
  {
    foreach ($theArray as $element)
    {
      if (is_scalar( $element ))
      {
        if ($element!==null && $element!==false && $element!=='')
        {
          return true;
        }
      }
      else
      {
        $tmp = $this->validateArray( $element );
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
