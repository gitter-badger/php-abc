<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class Ipv4TableRow extends TableRow
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
    $this->myDataType   = 'ipv4';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( &$theData )
  {
    return '<td class="ipv4">'.Html::txt2Html( $theData[$this->myFieldName] ).'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
