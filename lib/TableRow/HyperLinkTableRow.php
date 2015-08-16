<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;
use SetBased\Html\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
class HyperLinkTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a hyper link to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param string      $theValue  The hyper link.
   */
  public static function addRow( $theTable, $theHeader, $theValue )
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html( $theHeader );
    $row .= '</th><td>';
    if ($theValue!==null && $theValue!==false && $theValue!=='')
    {
      $url = Html::txt2Html( $theValue );
      $row .= '<a href="';
      $row .= $url;
      $row .= '"">';
      $row .= $url;
      $row .= '</a>';
    }
    $row .= '</td></tr>';

    $theTable->addRow( $row );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
