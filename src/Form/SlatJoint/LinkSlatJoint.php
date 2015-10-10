<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\LinkControl;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a hyperlink element.
 */
class LinkSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct($theHeaderText)
  {
    $this->myDataType   = 'control-link';
    $this->myHeaderHtml = Html::txt2Html($theHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a link form control.
   *
   * @param string $theName The local name of the link form control.
   *
   * @return LinkControl
   */
  public function createCell($theName)
  {
    return new LinkControl($theName);
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
