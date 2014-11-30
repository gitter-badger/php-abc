<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;
use SetBased\Html\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
class EmailTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a email address to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param string      $theValue  The email address.
   */
  public static function addRow( $theTable, $theHeader, $theValue )
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html( $theHeader );
    $row .= '</th><td class="email">';
    if ($theValue!==null && $theValue!==false && $theValue!=='')
    {
      $address = Html::Txt2Html( $theValue );
      $row .= '<a href="mailto:'.$address.'">'.$address.'</a>';
    }
    $row .= '</td></tr>';

    $theTable->addRow( $row );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
