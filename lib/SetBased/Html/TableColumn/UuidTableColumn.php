<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class UuidTableColumn extends TableColumn
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
   * @param string $theHeaderText The header text this table column.
   * @param string $theFieldName  The field name of the data row used for generating this table column.
   */
  public function __construct( $theHeaderText, $theFieldName )
  {
    $this->myDataType   = 'uuid';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( $theData )
  {
    return '<td>'.Html::txt2Html( $theData[$this->myFieldName] ).'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
