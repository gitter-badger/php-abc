<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class DivControl
 *
 * @package SetBased\Html\Form\Control
 */
class DivControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $ret = $this->myPrefix;

    $ret .= '<div';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= \SetBased\Html\Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    if (!empty($this->myAttributes['set_html']))
    {
      $ret .= $this->myAttributes['set_html']."\n";
    }
    $ret .= "</div>";

    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param null $theValues
   */
  public function setValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
