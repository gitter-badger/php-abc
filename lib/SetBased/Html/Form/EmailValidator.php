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
/** @brief Validates if the value of a form control (derived from FormControl) is a valid email address.
 * @note Can only be applied on form controls which values are strings.
 */
class EmailValidator implements ControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Returns @c true if
   *  * @a $theFormControl has no value.
   *  * The value of @a $theFormControl is a valid email address. The format of the email address
   *    is validated as well if the domain of the email address actually exists.
   *  Otherwise returns @c false.
   *
   * @param $theFormControl \SetBased\Html\Form\Control
   *
   * @return bool
   */
  public function validate( $theFormControl )
  {
    $value = $theFormControl->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null || $value===false)
    {
      return true;
    }

    // Objects and arrays are not valid email addresses.
    if (!is_scalar( $value ))
    {
      return false;
    }

    // Filter valid email address from the value.
    $email = filter_var( $value, FILTER_VALIDATE_EMAIL );

    // If the actual value and the filtered value are not equal the value is not a valid email address.
    if ($email!==$value)
    {
      return false;
    }

    // Test if the domain does exists.
    $domain = substr( strstr( $value, '@' ), 1 );
    if ($domain===false || $domain==='')
    {
      return false;
    }

    // The domain must have a MX or A record.
    if (!(checkdnsrr( $domain.'.', 'MX' ) || checkdnsrr( $domain.'.', 'A' )))
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
