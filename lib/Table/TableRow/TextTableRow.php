<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableRow;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table row in a detail table with plain text.
 */
class TextTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a text value to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param string      $theText   The text.
   */
  public static function addRow($theTable, $theHeader, $theText)
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html($theHeader);
    $row .= '</th><td class="text">';
    $row .= Html::txt2Html($theText);
    $row .= '</td></tr>';

    $theTable->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
