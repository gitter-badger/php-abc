<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SlatControl
 *
 * @package SetBased\Html\Form\Control
 */
class SlatControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of a tr element of this slat control.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $submit_name = $this->getSubmitName( $theParentName );

    $ret = '';
    foreach ($this->myControls as $control)
    {
      $ret .= '<td>';
      $ret .= $control->generate( $submit_name );
      $ret .= '</td>';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
