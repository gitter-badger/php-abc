<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Html\Html;

/** @brief Class for form controls of type input:text.
 */
class TextControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // By default whitespace is pruned from text form controls.
    $this->myCleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'text';
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

    // The user can enter any text in a input:text box. So, any value is white listed.
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

      // The value of a input:text must be a scalar.
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
}

//----------------------------------------------------------------------------------------------------------------------
