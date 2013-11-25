<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class InvisibleControl
 * Class for form controls of type input:hidden, hoever, the submitted value is never loaded.
 * @package SetBased\Html\Form\Control
 */
class InvisibleControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'hidden';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret  = $this->myPrefix;

    $ret .= "<input";
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';

    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Note: by definition the value of a input:invisible form control will not be changed, whatever is submitted.
    $value = $this->myAttributes['value'];

    $theWhiteListValue[$this->myName] = $value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theValues
   */
  public function setValuesBase( &$theValues )
  {
    if (isset($theValues[$this->myName]))
    {
      $value = $this->myAttributes[$this->myName];

      // The value of a input:hidden must be a scalar.
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
