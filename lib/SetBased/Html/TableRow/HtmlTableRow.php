<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class HtmlTableCell
 *
 * @package SetBased\Html\TableRow
 */
class HtmlTableRow extends TableRow
{
  /**
   * The field name of the data row used for generating this table row.
   *
   * @var string
   */
  protected $myFieldName;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table row.
   * @param string $theFieldName  The field name of the data row used for generating this table row.
   */
  public function __construct( $theHeaderText, $theFieldName )
  {
    $this->myDataType   = 'text';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( &$theData )
  {
    return '<td>'.$theData[$this->myFieldName].'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
