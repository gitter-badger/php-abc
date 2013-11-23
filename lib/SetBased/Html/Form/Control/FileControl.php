<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

/** @brief Class for form controls of type file.
 */
class FileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'file';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret  = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();

    $ret .= '<input';
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
  public function setValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if ($_FILES[$submit_name]['error']===0)
    {
      $theChangedInputs[$this->myName]  = $this;
      $theWhiteListValue[$this->myName] = $_FILES[$submit_name];
      $this->myAttributes['value']      = $_FILES[$submit_name];
    }
    else
    {
      $theWhiteListValue[$this->myName] = false;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
