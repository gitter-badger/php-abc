<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:radio](http://www.w3schools.com/tags/tag_input.asp).
 *
 * @todo  Add attribute for label.
 */
class RadioControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate($theParentName)
  {
    $this->myAttributes['type'] = 'radio';
    $this->myAttributes['name'] = $this->getSubmitName($theParentName);

    // A radio button is checked if and only if its value equals to the value of attribute value.
    if (isset($this->myAttributes['value']) && ((string)$this->myValue===(string)$this->myAttributes['value']))
    {
      $this->myAttributes['checked'] = true;
    }
    else
    {
      unset($this->myAttributes['checked']);
    }

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->myAttributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [value](http://www.w3schools.com/tags/att_input_value.asp).
   *
   * @param mixed $theValue The attribute value.
   */
  public function setAttrValue($theValue)
  {
    $this->myAttributes['value'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;
    $new_value   = (isset($theSubmittedValue[$submit_name])) ? (string)$theSubmittedValue[$submit_name] : null;

    if (isset($this->myAttributes['value']) && $new_value===(string)$this->myAttributes['value'])
    {
      if (empty($this->myAttributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->myAttributes['checked']    = true;
      $theWhiteListValue[$this->myName] = $this->myAttributes['value'];
      $this->myValue                    = $this->myAttributes['value'];
    }
    else
    {
      if (!empty($this->myAttributes['checked']))
      {
        $theChangedInputs[$this->myName] = $this;
      }
      $this->myAttributes['checked'] = false;
      $this->myValue                 = null;

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
