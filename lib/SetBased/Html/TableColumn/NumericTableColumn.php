<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class NumericTableColumn extends TableColumn
{
  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * The format specifier for formatting the content of this table column.
   *
   * @var string
   */
  protected $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   * @param string $theFieldName  The field name of the data row used for generating this table column.
   * @param string $theFormat     The format specifier for formatting the content of this table column. See sprintf.
   */
  public function __construct( $theHeaderText, $theFieldName, $theFormat = '%d' )
  {
    $this->myDataType   = 'numeric';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myFormat     = $theFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( $theData )
  {
    $value = $theData[$this->myFieldName];

    if ($value===false || $value===null || $value==='')
    {
      return '<td></td>';
    }
    else
    {
      return '<td class="number">'.Html::txt2Html( sprintf( $this->myFormat, $value ) ).'</td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
