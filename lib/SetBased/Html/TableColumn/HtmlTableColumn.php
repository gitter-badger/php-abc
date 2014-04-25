<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class HtmlTableCell
 *
 * @package SetBased\Html\TableColumn
 */
class HtmlTableColumn extends TableColumn
{
  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   * @param string $theFieldName  The field name of the data row used for generating this table column.
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
