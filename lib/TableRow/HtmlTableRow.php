<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Html;
use SetBased\Html\Table\DetailTable;

/**
 * Class HtmlTableCell
 *
 * @package SetBased\Html\TableRow
 */
class HtmlTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a HTML snippet to a detail table.
   *
   * @param DetailTable $theTable       The (detail) table.
   * @param string      $theHeader      The row header text.
   * @param string      $theHtmlSnippet The HTML snippet.
   */
  public static function addRow( $theTable, $theHeader, $theHtmlSnippet )
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html( $theHeader );
    $row .= '</th><td class="html">';
    $row .= $theHtmlSnippet;
    $row .= '</td></tr>';

    $theTable->addRow( $row );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
