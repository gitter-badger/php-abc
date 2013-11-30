<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class FileControl
 * Class for form controls of type file.
 * @package SetBased\Html\Form\Control
 */
class FileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theParentName
   *
   * @return string
   */
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
  /**
   * @param string|bool $theValue The new value for the form control.
   */
  public function setValue( $theValue )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if ($_FILES[$submit_name]['error']===0)
    {
      $theChangedInputs[$this->myName]  = $this;
      $theWhiteListValue[$this->myName] = $_FILES[$submit_name];
      $this->myValue                    = $_FILES[$submit_name];
    }
    else
    {
      $this->myValue                    = null;
      $theWhiteListValue[$this->myName] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
