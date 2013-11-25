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
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'radio';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret = $this->myPrefix;
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
   * @param array $theValues
   */
  public function setValuesBase( &$theValues )
  {
    if (isset($theValues[$this->myName]))
    {
      $value = $theValues[$this->myName];

      // The value of a input:checkbox must be a scalar.
      if (!is_scalar( $value ))
      {
        Html::error( "Illegal value '%s' for form control '%s'.", $value, $this->myName );
      }

      /** @todo unset when empty? */
      $this->myAttributes['checked'] = !empty($value);
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['checked']);
    }
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

    if ((string)$theSubmittedValue[$submit_name]===(string)$this->myAttributes['value'])
    {
      if (empty($this->myAttributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->myAttributes['checked']    = true;
      $theWhiteListValue[$this->myName] = $this->myAttributes['value'];

      // Set the submitted value to be used method GetSubmittedValue.
      $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$this->myName];
    }
    else
    {
      if (!empty($this->myAttributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->myAttributes['checked'] = false;

      if (!array_key_exists( $this->myName, $theWhiteListValue ))
      {
        $theWhiteListValue[$this->myName] = null;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
