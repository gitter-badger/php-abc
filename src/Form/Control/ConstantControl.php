<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for pseudo form controls for form controls of which the value is constant.
 */
class ConstantControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an empty string.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate($theParentName)
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A constant control must never be shown in a table.
   *
   * @param string $theParentName Not used.
   *
   * @return string An empty string.
   */
  public function getHtmlTableCell($theParentName)
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * Note:
   * Always sets the white listed value to the value of this constant form control.
   * Never uses $theSubmittedValue and never sets the $theChangedInputs.
   */
  protected function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $theWhiteListValue[$this->myName] = $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
