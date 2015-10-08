<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableRow;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table row in a detail table with an IPv4 address.
 */
class Ipv4TableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a IPv4 value to a detail table.
   *
   * @param DetailTable $theTable      The (detail) table.
   * @param string      $theHeader     The row header text.
   * @param string      $theIp4Address The IPv4 address.
   */
  public static function addRow($theTable, $theHeader, $theIp4Address)
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html($theHeader);
    $row .= '</th><td class="ipv4">';
    $row .= Html::txt2Html($theIp4Address);
    $row .= '</td></tr>';

    $theTable->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
