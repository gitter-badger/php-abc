<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;
use SetBased\Html\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
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
  public static function addRow( $theTable, $theHeader, $theText )
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html( $theHeader );
    $row .= '</th><td class="text">';
    $row .= Html::txt2Html( $theText );
    $row .= '</td></tr>';

    $theTable->addRow( $row );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
