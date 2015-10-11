<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type input:image.
 */
class ImageControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'image'.
   *
   * @param string $theParentName The submit name of the parent form control.
   *
   * @return string
   */
  public function generate($theParentName)
  {
    $this->myAttributes['type'] = 'image';

    // For images we use local names. It is the task of the developer to ensure the local names of buttons
    // are unique.
    $this->myAttributes['name'] = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->myAttributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [alt](http://www.w3schools.com/tags/att_input_alt.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttr($theValue)
  {
    $this->myAttributes['alt'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formaction](http://www.w3schools.com/tags/att_input_formaction.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormAction($theValue)
  {
    $this->myAttributes['formaction'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formenctype](http://www.w3schools.com/tags/att_input_formenctype.asp). Possible values:
   * * application/x-www-form-urlencoded (default)
   * * multipart/form-data
   * * text/plain
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormEncType($theValue)
  {
    $this->myAttributes['formenctype'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formmethod](http://www.w3schools.com/tags/att_input_formmethod.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormMethod($theValue)
  {
    $this->myAttributes['formmethod'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formtarget](http://www.w3schools.com/tags/att_input_formtarget.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrFormTarget($theValue)
  {
    $this->myAttributes['formtarget'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [height](http://www.w3schools.com/tags/att_input_height.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrHeight($theValue)
  {
    $this->myAttributes['height'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [src](http://www.w3schools.com/tags/att_input_src.asp).
   *
   * @param string $theValue The attribute value.
   */
  public function setAttrSrc($theValue)
  {
    $this->myAttributes['src'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [width](http://www.w3schools.com/tags/att_input_width.asp).
   *
   * @param int $theValue The attribute value.
   */
  public function setAttrWidth($theValue)
  {
    $this->myAttributes['width'] = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string|bool $theValue .
   */
  public function setValue($theValue)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    throw new LogicException('Not implemented.');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
