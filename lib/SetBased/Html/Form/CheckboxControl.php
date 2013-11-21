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
  /** @brief Class for form controls of type input:checkbox.
   * @todo Add attribute for label.
   */
use SetBased\Html\Html;

/**
 * Class CheckboxControl
 * @package SetBased\Html\Form
 */
class CheckboxControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'checkbox';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

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
  /**
   * @param $theValues mixed
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
   * @param  $theSubmittedValue array
   * @param  $theWhiteListValue array
   * @param  $theChangedInputs  array
   *
   * @return mixed|void
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $submit_name = ($obfuscator) ? $obfuscator->encode( $this->myName ) : $this->myName;

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
