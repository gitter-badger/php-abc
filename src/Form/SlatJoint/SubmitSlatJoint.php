<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a input:submit form control.
 */
class SubmitSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->myDataType   = 'control-reset';
    $this->myHeaderHtml = Html::txt2Html($theHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a reset form control.
   *
   * @param string $theName The local name of the submit form control.
   *
   * @return SubmitControl
   */
  public function createCell($theName)
  {
    return new SubmitControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    return '<td></td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
