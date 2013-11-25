<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * @brief Abstract class for generation HTML code for table rows in a DetailTable.
 */
abstract class TableRow
{
  /**
   * The type of the data that this table row holds.
   *
   * @var string
   */
  protected $myDataType;

  /**
   * The header text of this table row.
   *
   * @var string
   */
  protected $myHeaderText;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing td tags) for the table cell.
   *
   * @param array $theData
   *
   * @return string
   */
  abstract public function getHtmlCell( &$theData );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table header cell.
   *
   * @return string
   */
  public function getHtmlRowHeader()
  {
    return "<th>".Html::txt2Html( $this->myHeaderText )."</th>\n";
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
