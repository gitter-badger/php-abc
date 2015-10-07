<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A pseudo table action showing the row count in a (overview) table body.
 */
class RowCountTableAction implements TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The number of rows in the table body.
   *
   * @var int
   */
  protected $myRowCount;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theRowCount The number of rows in the table body.
   */
  public function __construct($theRowCount)
  {
    $this->myRowCount = $theRowCount;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtml()
  {
    return '<span class="row_count">'.Html::txt2Html($this->myRowCount).'</span>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
