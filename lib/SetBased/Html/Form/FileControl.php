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

    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    $ret .= $this->generatePrefixLabel();
    $ret .= '<input';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
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
      $theChangedInputs[$this->myName]  = true;
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
