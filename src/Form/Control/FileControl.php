<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type input:file.
 */
class FileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'file'.
   *
   * @param $theParentName
   *
   * @return string
   */
  public function generate($theParentName)
  {
    $this->myAttributes['type'] = 'file';
    $this->myAttributes['name'] = $this->getSubmitName($theParentName);

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->myAttributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Does nothing. It is not possible the set the value of a file form control.
   *
   * @param string $theValue Not used.
   */
  public function setValue($theValue)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accept](http://www.w3schools.com/tags/att_input_accept.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrForm($theValue)
  {
    $this->myAttributes['accept'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if ($_FILES[$submit_name]['error']===0)
    {
      $theChangedInputs[$this->myName]  = $this;
      $theWhiteListValue[$this->myName] = $_FILES[$submit_name];
      $this->myValue                    = $_FILES[$submit_name];
    }
    else
    {
      $this->myValue                    = null;
      $theWhiteListValue[$this->myName] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
