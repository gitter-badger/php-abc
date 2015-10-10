<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a complex form control.
 */
class ComplexSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->myDataType   = 'control-complex';
    $this->myHeaderHtml = Html::txt2Html($theHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a complex form control.
   *
   * @param string $theName The local name of the complex form control.
   *
   * @return ComplexControl
   */
  public function createCell($theName)
  {
    return new ComplexControl($theName);
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
