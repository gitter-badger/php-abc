<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 * @par Copyright:
 * Set Based IT Consultancy
 * $Date: 2013/03/04 19:02:37 $
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Validates if a form control has a value.
 * @note Can be applied on any form control object.
 */
class MandatoryValidator implements ControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Validates recursively if one of the leaves of @a $theArray has a non-empty value.
   *
   * @param $theArray A nested array.
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
}

//----------------------------------------------------------------------------------------------------------------------
