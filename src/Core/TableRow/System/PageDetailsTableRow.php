<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableRow\System;

use SetBased\Abc\Core\Page\System\PageDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 *
 */
class PageDetailsTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a class name of a page with link to the page details to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param array       $theData   The page details.
   */
  public static function addRow($theTable, $theHeader, $theData)
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html($theHeader);
    $row .= '</th><td class="text"><a';
    $row .= Html::generateAttribute('href', PageDetailsPage::getUrl($theData['pag_id_org']));
    $row .= '>';
    $row .= $theData['pag_id_org'];
    $row .= '</a></td></tr>';

    $theTable->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
