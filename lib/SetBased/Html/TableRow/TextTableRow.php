<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class TextTableRow extends TableRow
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
   * @param string $theHeaderText The header text this table row.
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
    return '<td>'.Html::txt2Html( $theData[$this->myFieldName] ).'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
