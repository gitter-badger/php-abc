<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\Control;

use SetBased\Abc\Form\Control\ComplexControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A complex control with buttons.
 */
class CoreButtonControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $html = $this->myPrefix;
    $html .= '<table class="button">';
    $html .= '<tr>';

    foreach ($this->myControls as $control)
    {
      $html .= '<td>';
      $html .= $control->generate();
      $html .= '</td>';
    }

    $html .= '</tr>';
    $html .= '</table>';
    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
