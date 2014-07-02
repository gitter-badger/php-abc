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
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'checkbox'.
   *       * checked Will be replaced with 'checked' if @a $myValue is not empty, otherwise will be empty.
   *
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
      // Ignore attributes starting with an underscore.
      if ($name[0]!='_') $html .= Html::generateAttribute( $name, $value );
    }
    $html .= '/>';

    $html .= $this->generatePostfixLabel();
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
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
