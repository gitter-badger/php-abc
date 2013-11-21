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
/** @brief Class for form controls of type input:hidden.
 */
class HiddenControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'hidden';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

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

    if (isset($this->myAttributes['set_clean']))
    {
      $new_value = call_user_func( $this->myAttributes['set_clean'], $theSubmittedValue[$submit_name] );
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }
    // Normalize old (original) value and new (submitted) value.
    $old_value = (isset($this->myAttributes['value'])) ? (string)$this->myAttributes['value'] : '';
    $new_value = (string)$new_value;
      $old_value = '';
    }
    if ($new_value==='' || $new_value===null || $new_value===false)
    {
      $new_value = '';
    }

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$this->myName] = true;
      $this->myAttributes['value']     = $new_value;
    }

    // Any text can be in a input:hidden box. So, any value is white listed.
    $theWhiteListValue[$this->myName] = $new_value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    if (isset($theValues[$this->myName]))
    {
      $value = $theValues[$this->myName];

      // The value of a input:hidden must be a scalar.
      if (!is_scalar( $value ))
      {
        \SetBased\Html\Html::error( "Illegal value '%s' for form control '%s'.", $value, $this->myName );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['value'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
