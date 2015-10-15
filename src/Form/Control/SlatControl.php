<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A pseudo form control for generating table rows in a Louver control.
 */
class SlatControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    // Create start tag of table row.
    $ret = Html::generateTag('tr', $this->myAttributes);

    // Create table cells.
    foreach ($this->myControls as $control)
    {
      $ret .= $control->getHtmlTableCell();
    }

    // Create table cell with error message, if any.
    $ret .= $this->generateErrorCell();

    // Create end tag of table row.
    $ret .= '</tr>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a table cell with the errors messages of all form controls at this row.
   *
   * @return string
   */
  protected function generateErrorCell()
  {
    $ret = '';

    if (!$this->isValid())
    {
      $error_messages = $this->getErrorMessages(true);

      $ret .= '<td class="error">';
      foreach ($error_messages as $message)
      {
        $ret .= Html::txt2Html($message);
        $ret .= '<br/>';
      }
      $ret .= '</td>';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
