<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\ButtonControl;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Html\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
class TableColumnSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param TableColumn $theTableColumn
   */
  public function __construct($theTableColumn)
  {
    $this->myDataType    = $theTableColumn->getDataType();
    $this->myTableColumn = $theTableColumn;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a button form control.
   *
   * @param string $theName The local name of the button form control.
   *
   * @return ButtonControl
   */
  public function createCell($theName)
  {
    $control = new TableColumnControl($theName);
    $control->setTableColumn($this->myTableColumn);

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getColSpan()
  {
    return $this->myTableColumn->getColSpan();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for col element for this slat joint.
   *
   * @return string
   */
  public function getHtmlColumn()
  {
    return $this->myTableColumn->getHtmlColumn();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    return $this->myTableColumn->getHtmlColumnFilter();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlColumnHeader()
  {
    return $this->myTableColumn->getHtmlColumnHeader();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
