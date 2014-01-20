<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\SubmitControl;
use SetBased\Html\Html;

class SubmitSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct( $theHeaderText )
  {
    $this->myDataType   = 'control_reset';
    $this->myHeaderHtml = Html::txt2Html( $theHeaderText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a reset form control.
   *
   * @param string $theName The local name of the submit form control.
   *
   * @return SubmitControl
   */
  public function createCell( $theName )
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
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
