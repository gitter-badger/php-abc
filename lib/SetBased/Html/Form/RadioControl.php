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
use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:radio.
 *
 * @todo Add attribute for label.
 */
class RadioControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName  )
  {
    $this->myAttributes['type'] = 'radio';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    $ret .= $this->generatePrefixLabel();
    $ret .= "<input";
    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= \SetBased\Html\Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';
    $ret .= $this->generatePostfixLabel();

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $submit_name = ($obfuscator) ? $obfuscator->encode( $this->myName ) : $this->myName;

    if ((string)$theSubmittedValue[$submit_name]===(string)$this->myAttributes['value'])
    {
      if (empty($this->myAttributes['checked'])) $theChangedInputs[$this->myName] = true;
      $this->myAttributes['checked']  = true;
      $theWhiteListValue[$this->myName] = $this->myAttributes['value'];

      // Set the submitted value to be used method GetSubmittedValue.
      $this->myAttributes['set_submitted_value'] =  $theWhiteListValue[$this->myName];
    }
    else
    {
      if (!empty($this->myAttributes['checked'])) $theChangedInputs[$this->myName] = true;
      $this->myAttributes['checked'] = false;

      if (!array_key_exists( $this->myName, $theWhiteListValue )) $theWhiteListValue[$this->myName] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    if (isset($theValues[$this->myName]))
    {
      $value = $theValues[$this->myName];

      // The value of a input:checkbox must be a scalar.
      if (!is_scalar($value))
      {
        \SetBased\Html\Html::error( "Illegal value '%s' for form control '%s'.", $value, $this->myName );
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
}

//----------------------------------------------------------------------------------------------------------------------
