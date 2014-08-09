<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ConstantControl
 *
 * @package SetBased\Html\Form\Control
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
  public function generate( $theParentName )
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
  public function getHtmlTableCell( $theParentName )
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   * Note:
   * Always sets the white listed value to the value of this constant form control.
   * Never uses $theSubmittedValue and never sets the $theChangedInputs.
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $theWhiteListValue[$this->myName] = $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param null $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
