<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------

/**
 * Class CheckboxControl
 * Class for form controls of type input:checkbox.
 *
 * @todo    Add attribute for label.
 * @package SetBased\Html\Form
 */
class CheckboxControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'checkbox';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret = $this->myPrefix;
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
  /**
   * @param mixed $theValues
   *
   * @return mixed|void
   */
  public function setValuesBase( &$theValues )
  {
    if (isset($theValues[$this->myName]))
    {
      $value = $theValues[$this->myName];

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
   *
   * @return mixed|void
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if (empty($this->myAttributes['checked'])!==empty($theSubmittedValue[$submit_name]))
    {
      $theChangedInputs[$this->myName] = $this;
    }

    /** @todo Decide whether to test submited value is white listed, i.e. $this->myAttributes['value'] (or 'on'
     *  if $this->myAttributes['value'] is null) or null.
     */
    if (!empty($theSubmittedValue[$submit_name]))
    {
      $this->myAttributes['checked']    = true;
      $this->myAttributes['value']      = $theSubmittedValue[$submit_name];
      $theWhiteListValue[$this->myName] = true;
    }
    else
    {
      $this->myAttributes['checked']    = false;
      $this->myAttributes['value']      = '';
      $theWhiteListValue[$this->myName] = false;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $this->myAttributes['checked'];
  }

  //--------------------------------------------------------------------------------------------------------------------
}
//----------------------------------------------------------------------------------------------------------------------
