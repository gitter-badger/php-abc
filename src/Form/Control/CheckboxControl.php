<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type [input:checkbox](http://www.w3schools.com/tags/tag_input.asp).
 *
 * @todo    Add attribute for label.
 */
class CheckboxControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'checkbox'.
   *       * checked Will be replaced with 'checked' if @a $myValue is not empty, otherwise will be empty.
   *
   * @return string
   */
  public function generate()
  {
    $this->myAttributes['type']    = 'checkbox';
    $this->myAttributes['name']    = $this->mySubmitName;
    $this->myAttributes['checked'] = $this->myValue;

    $html = $this->myPrefix;
    $html .= $this->generatePrefixLabel();
    $html .= Html::generateVoidElement('input', $this->myAttributes);
    $html .= $this->generatePostfixLabel();
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control in a table cell.
   *
   * @return string
   */
  public function getHtmlTableCell()
  {
    return '<td class="control checkbox">'.$this->generate().'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    /** @todo Decide whether to test submitted value is white listed, i.e. $this->myAttributes['value'] (or 'on'
     *  if $this->myAttributes['value'] is null) or null.
     */

    if (empty($this->myValue)!==empty($theSubmittedValue[$submit_name]))
    {
      $theChangedInputs[$this->myName] = $this;
    }

    if (!empty($theSubmittedValue[$submit_name]))
    {
      $this->myValue                    = true;
      $theWhiteListValue[$this->myName] = true;
    }
    else
    {
      $this->myValue                    = false;
      $theWhiteListValue[$this->myName] = false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}
//----------------------------------------------------------------------------------------------------------------------
