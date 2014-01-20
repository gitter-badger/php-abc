<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\FileControl;
use SetBased\Html\Html;

class FileSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct( $theHeaderText )
  {
    $this->myDataType   = 'control_file';
    $this->myHeaderHtml = Html::txt2Html( $theHeaderText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a file form control.
   *
   * @param string $theName The local name of the file form control.
   *
   * @return FileControl
   */
  public function createCell( $theName )
  {
    return new FileControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
