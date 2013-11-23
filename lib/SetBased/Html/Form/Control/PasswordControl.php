<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Html\Html;

/** @brief Class for form controls of type input:password.
 */
class PasswordControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // By default whitespace is trimmed from password form controls.
    $this->myCleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'password';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    if (isset($this->myAttributes['maxlength']))
    {
      if (isset($this->myAttributes['size']))
      {
        $this->myAttributes['size'] = min( $this->myAttributes['size'], $this->myAttributes['maxlength'] );
      }
      else
      {
        $this->myAttributes['size'] = $this->myAttributes['maxlength'];
      }
    }


    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    $ret .= $this->generatePrefixLabel();
    $ret .= "<input";
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
    if (isset($theValues[$this->myName]))
    {
      $value = $theValues[$this->myName];

      // The value of a input:password must be a scalar.
      if (!is_scalar( $value ))
      {
        Html::error( "Illegal value '%s' for form control '%s'.", $value, $this->myName );
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
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    // Get the submitted value and cleaned (if required).
    if ($this->myCleaner)
    {
      $new_value = $this->myCleaner->clean( $theSubmittedValue[$submit_name] );
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }

    // Normalize old (original) value and new (submitted) value.
    $old_value = (isset($this->myAttributes['value'])) ? (string)$this->myAttributes['value'] : '';
    $new_value = (string)$new_value;

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myAttributes['value']     = $new_value;
    }

    // The user can enter any text in a input:password box. So, any value is white listed.
    $theWhiteListValue[$this->myName] = $new_value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
