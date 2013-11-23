<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

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
  /**
   * @param $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = $this->myButtonType;

    // For buttons we use local names. It is the task of the developer to ensure the local names of buttons
    // are unique.
    $this->myAttributes['name'] = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    $ret  = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();

    $ret .= "<input";
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';

    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theSubmittedValue array
   * @param $theWhiteListValue array
   * @param $theChangedInputs  array
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if (isset($theSubmittedValue[$submit_name]) && $theSubmittedValue[$submit_name]===$this->myAttributes['value'])
    {
      // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
      // $theChangedInputs[$this->myName] = $this;

      $theWhiteListValue[$this->myName] = $this->myAttributes['value'];
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $this->myAttributes['value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theValues mixed
   *
   * @return mixed|void
   */
  public function setValuesBase( &$theValues )
  {
    // We don't set the value of a button via Form::setValues() method. So, nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
