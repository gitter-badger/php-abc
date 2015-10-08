<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableRow;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table row in a detail table with a number.
 */
class NumericTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a numeric value to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param string      $theValue  The text.
   * @param string      $theFormat The formatting string (see sprintf).
   */
  public static function addRow($theTable, $theHeader, $theValue, $theFormat)
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html($theHeader);
    $row .= '</th><td class="number">';
    if ($theValue!==null && $theValue!==false && $theValue!=='')
    {
      $row .= Html::txt2Html(sprintf($theFormat, $theValue));
    }
    $row .= '</td></tr>';

    $theTable->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
