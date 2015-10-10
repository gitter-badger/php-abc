<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\TrimWhitespaceCleaner;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type textarea.
 */
class TextAreaControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    // By default whitespace is trimmed from textarea form controls.
    $this->myCleaner = TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate($theParentName)
  {
    $this->myAttributes['name'] = $this->getSubmitName($theParentName);

    $html = $this->myPrefix;
    $html .= Html::generateElement('textarea', $this->myAttributes, $this->myValue);
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    // Get the submitted value and cleaned (if required).
    if ($this->myCleaner)
    {
      $new_value = $this->myCleaner->clean($theSubmittedValue[$submit_name]);
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }

    if ((string)$this->myValue!==(string)$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myValue                   = $new_value;
    }

    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
