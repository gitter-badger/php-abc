<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Table\TableColumn;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column for table cells with IPv4 addresses.
 */
class Ipv4TableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
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
  public function __construct($theHeaderText, $theFieldName)
  {
    $this->myDataType   = 'ipv4';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($theData)
  {
    return '<td class="ipv4">'.Html::txt2Html($theData[$this->myFieldName]).'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
