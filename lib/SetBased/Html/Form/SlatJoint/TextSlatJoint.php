<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\TextControl;
use SetBased\Html\Html;

class TextSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct( $theHeaderText )
  {
    $this->myDataType   = 'input_text';
    $this->myHeaderHtml = Html::txt2Html( $theHeaderText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a text form control.
   *
   * @param string $theName The local name of the text form control.
   *
   * @return TextControl
   */
  public function createCell( $theName )
  {
    return new TextControl( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
