<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

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

    // Create opening tag of table row.
    $ret = '<tr';
    foreach ($this->myAttributes as $name => $value)
    {
      // Ignore attributes starting with an underscore.
      if ($name[0]!='_') $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';

    // Create table cells.
    foreach ($this->myControls as $control)
    {
      $ret .= $control->getHtmlTableCell( $submit_name );
    }

    // Create table cell with error message, if any.
    $ret .= $this->generateErrorCell();

    // Create closing tag of table row.
    $ret .= '</tr>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateErrorCell()
  {
    $ret = '';

    if (!$this->isValid())
    {
      $error_messages = $this->getErrorMessages( true );

      $ret .= '<td class="error">';
      foreach( $error_messages as $message )
      {
        $ret .= Html::txt2Html( $message );
        $ret.= '<br/>';
      }
      $ret .= '</td>';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
