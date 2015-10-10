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
