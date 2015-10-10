<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type input:hidden.
 */
class HiddenControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'hidden'.
   *       * value   Will be replace with the (formatted) values of this form control.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate($theParentName)
  {
    $this->myAttributes['type'] = 'hidden';
    $this->myAttributes['name'] = $this->getSubmitName($theParentName);

    if ($this->myFormatter) $this->myAttributes['value'] = $this->myFormatter->format($this->myValue);
    else                    $this->myAttributes['value'] = $this->myValue;

    $ret = $this->myPrefix;
    $ret .= Html::generateVoidElement('input', $this->myAttributes);
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A hidden control must never be shown in a table.
   *
   * @param string $theParentName Not used.
   *
   * @return string An empty string.
   */
  public function getHtmlTableCell($theParentName)
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    // Get the submitted value and clean it (if required).
    if ($this->myCleaner)
    {
      $new_value = $this->myCleaner->clean($theSubmittedValue[$submit_name]);
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }

    // Normalize old (original) value and new (submitted) value.
    $old_value = (string)$this->myValue;
    $new_value = (string)$new_value;

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myValue                   = $new_value;
    }

    // Any text can be in a input:hidden box. So, any value is white listed.
    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
