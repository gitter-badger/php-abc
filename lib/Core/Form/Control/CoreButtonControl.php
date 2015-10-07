<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\Control;

use SetBased\Html\Form\Control\ComplexControl;

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
  public function generate($theParentName)
  {
    $submit_name = $this->getSubmitName($theParentName);

    $html = $this->myPrefix;
    $html .= '<table class="button">';
    $html .= '<tr>';

    foreach ($this->myControls as $control)
    {
      $html .= '<td>';
      $html .= $control->generate($submit_name);
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
