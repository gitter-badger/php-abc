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
    $this->myAttributes['type']    = 'checkbox';
    $this->myAttributes['name']    = $this->getSubmitName( $theParentName );
    $this->myAttributes['checked'] = $this->myValue;

    $html = $this->myPrefix;
    $html .= $this->generatePrefixLabel();

    $html .= '<input';
    foreach ($this->myAttributes as $name => $value)
    {
      $html .= Html::generateAttribute( $name, $value );
    }
    $html .= '/>';

    $html .= $this->generatePostfixLabel();
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of the this checkbox. If @a $theValue is not empty then this checkbox is checked otherwise this
   * checkbox is unchecked.
   *
   * @param mixed $theValue
   */
  public function setValue( $theValue )
  {
    if (!empty($theValue))
    {
      $this->myAttributes['checked'] = true;
      $this->myValue                 = true;
    }
    else
    {
      $this->myAttributes['checked'] = false;
      $this->myValue                 = false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute with name @a $theName of this form control to @a $theValue. If @a $theValue is
   *
   * @c null, @c false, or @c '' the attribute is unset.
   *
   * @param string $theName  The name of the attribute.
   * @param mixed  $theValue The value for the attribute.
   */
  public function setAttribute( $theName, $theValue )
  {
    if ($theName=='checked')
    {
      $this->setValue( $theValue );
    }
    else
    {
      Control::setAttribute( $theName, $theValue );
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

    /** @todo Decide whether to test submitted value is white listed, i.e. $this->myAttributes['value'] (or 'on'
     *  if $this->myAttributes['value'] is null) or null.
     */

    if (empty($this->myValue)!==empty($theSubmittedValue[$submit_name]))
    {
      $theChangedInputs[$this->myName] = $this;
    }

    if (!empty($theSubmittedValue[$submit_name]))
    {
      $this->myAttributes['checked']    = true;
      $this->myValue                    = true;
      $theWhiteListValue[$this->myName] = true;
    }
    else
    {
      $this->myAttributes['checked']    = false;
      $this->myValue                    = false;
      $theWhiteListValue[$this->myName] = false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}
//----------------------------------------------------------------------------------------------------------------------
