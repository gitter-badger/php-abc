<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class RadioControl
 * Class for form controls of type input:radio.
 *
 * @todo    Add attribute for label.
 * @package SetBased\Html\Form\Control
 */
class RadioControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'radio'.
   *       * value   Will be replaced with the value of this form control.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type']  = 'radio';
    $this->myAttributes['name']  = $this->getSubmitName( $theParentName );
    $this->myAttributes['value'] = $this->myValue;

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();

    $ret .= '<input';
    foreach ($this->myAttributes as $name => $value)
    {
      // Ignore attributes starting with an underscore.
      if ($name[0]!='_') $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';

    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if ((string)$theSubmittedValue[$submit_name]===(string)$this->myValue)
    {
      if (empty($this->myAttributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->myAttributes['checked']    = true;
      $theWhiteListValue[$this->myName] = $this->myValue;
    }
    else
    {
      if (!empty($this->myAttributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->myAttributes['checked'] = false;

      // If the white listed value is not set by a radio button with the same name as this radio button, set the white
      // listed value of this radio button (and other radio buttons with the same name) to null. If another radio button
      // with the same name is checked the white listed value will be overwritten.
      if (!isset($theWhiteListValue[$this->myName]))
      {
        $theWhiteListValue[$this->myName] = null;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
