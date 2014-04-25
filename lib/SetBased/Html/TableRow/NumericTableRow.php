<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class NumericTableRow extends TableRow
{
  /**
   * The field name of the data row used for generating this table row.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * The format specifier for formatting the content of this table row.
   *
   * @var string
   */
  protected $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table row.
   * @param string $theFieldName  The field name of the data row used for generating this table row.
   * @param string $theFormat     The format specifier for formatting the content of this table row. See sprintf.
   */
  public function __construct( $theHeaderText, $theFieldName, $theFormat='%d' )
  {
    $this->myDataType   = 'numeric';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myFormat     = $theFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( &$theData )
  {
    $value = $theData[$this->myFieldName];

    if ($value===false || $value===null || $value==='')
    {
      return '<td class="number"></td>';
    }
    else
    {
      return '<td class="number">'.Html::txt2Html( sprintf( $this->myFormat, $value ) ).'</td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
