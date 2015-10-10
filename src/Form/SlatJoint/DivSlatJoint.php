<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\DivControl;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a div element.
 */
class DivSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->myDataType   = 'control-div';
    $this->myHeaderHtml = Html::txt2Html($theHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a div form control.
   *
   * @param string $theName The local name of the div form control.
   *
   * @return DivControl
   */
  public function createCell($theName)
  {
    return new DivControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    return '<td><input type="text"/></td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
