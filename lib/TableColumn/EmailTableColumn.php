<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for cells with an email address.
 */
class EmailTableColumn extends TableColumn
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
   * @param string $theHeaderText The header text for this table column.
   * @param string $theFieldName  The field name of the data rows used for generating this table column.
   */
  public function __construct( $theHeaderText, $theFieldName )
  {
    $this->myDataType   = 'email';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell( $theData )
  {
    $value = $theData[$this->myFieldName];

    if ($value!==false && $value!==null && $value!=='')
    {
      // The value holds an email address.
      $address = Html::txt2Html( $value );

      return '<a '.Html::generateAttribute( 'href', 'mailto:'.$address ).'>'.Html::txt2Html( $address ).'</a>';
    }
    else
    {
      // The value is empty.
      return '<td></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
