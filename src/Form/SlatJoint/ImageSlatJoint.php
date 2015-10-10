<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\ImageControl;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a input:image form control.
 */
class ImageSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->myDataType   = 'control-image';
    $this->myHeaderHtml = Html::txt2Html($theHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a image form control.
   *
   * @param string $theName The local name of the image form control.
   *
   * @return ImageControl
   */
  public function createCell($theName)
  {
    return new ImageControl($theName);
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
