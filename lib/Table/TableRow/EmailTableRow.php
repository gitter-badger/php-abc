<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableRow;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table row in a detail table with aa email address.
 */
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
  public static function addRow($theTable, $theHeader, $theValue)
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html($theHeader);
    $row .= '</th><td class="email">';
    if ($theValue!==null && $theValue!==false && $theValue!=='')
    {
      $address = Html::txt2Html($theValue);
      $row .= '<a href="mailto:';
      $row .= $address;
      $row .= '">';
      $row .= $address;
      $row .= '</a>';
    }
    $row .= '</td></tr>';

    $theTable->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
