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

use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Base class for form controls submit, reset, and button
 */
class PushMeControl extends SimpleControl
{
  /** The type of this button. Valid values are:
   *  \li submit
   *  \li reset
   *  \li button
   */
  protected $myButtonType;

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = $this->myButtonType;

    // For buttons we use local names. It is the task of the developer to ensure the local names of buttons
    // are unique.
    $obfuscator                 = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $this->myAttributes['name'] = ($obfuscator) ? $obfuscator->encode( $this->myName ) : $this->myName;

    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    // print_r( $this);
    $ret .= $this->generatePrefixLabel();
    $ret .= "<input";
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= \SetBased\Html\Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';
    $ret .= $this->generatePostfixLabel();

    if (isset($this->myAttributes['set_postfix']))
    {
      $ret .= $this->myAttributes['set_postfix'];
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $submit_name = ($obfuscator) ? $obfuscator->encode( $this->myName ) : $this->myName;

    if ($theSubmittedValue[$submit_name]===$this->myAttributes['value'])
    {
      // We don't register buttons as a changed input, otherwise every submited form will always have changed inputs.
      // $theChangedInputs[$this->myName] = true;

      $theWhiteListValue[$this->myName] = $this->myAttributes['value'];
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $this->myAttributes['value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    // We don't set the value of a button via Form::setValues() method. So, nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
