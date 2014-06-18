<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class EmailTableRow extends TableRow
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
   * @param string $theHeaderText The header text for this table row.
   * @param string $theFieldName  The field name of the data rows used for generating this table row.
   */
  public function __construct( $theHeaderText, $theFieldName )
  {
    $this->myDataType   = 'email';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( $theData )
  {
    $address = Html::Txt2Html( $theData[$this->myFieldName] );
    return '<td><a href="mailto:'.$address.'">'.$address.'</a></td>\n';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
