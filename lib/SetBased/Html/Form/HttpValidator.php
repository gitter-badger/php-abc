<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 *
 * @par Copyright:
 * Set Based IT Consultancy
 *
 * $Date: 2013/03/04 19:02:37 $
 *
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Validates if the value of a form control (derived from FormControl) is a valid http URL.
 *
 * @note Can only be applied on form controls which values are strings.
 */
class HttpValidator implements ControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @a true if @a $theFormControl has no value.
   *  Returns @a true if the value of @a $theFormControl is a valid http URL.
   *  Otherwise returns @a false.
   */
  public function validate( $theFormControl )
  {
    $value = $theFormControl->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null || $value===false) return true;

    // Objects and arrays are not a valid http URL.
    if (!is_scalar($value)) return false;

    // Filter valid URL from the value.
    $url = filter_var( $value, FILTER_VALIDATE_URL );

    // If the actual value and the filtered value are not equal the value is not a valid url.
    if ($url!==$value) return false;

    // filter_var allows not to specify the HTTP protocol. Test the URL starts with http (or https).
    if (substr( $url, 0, 4 )!=='http') return false;

    // Test that the page actually exits. We consider all HTTP 200-399 responses are valid.
    $hdrs = get_headers( $url );
    $ok   = (is_array($hdrs) && preg_match('/^HTTP\\/\\d+\\.\\d+\\s+[23]\\d\\d\\s*.*$/', $hdrs[0]));

    return $ok;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
