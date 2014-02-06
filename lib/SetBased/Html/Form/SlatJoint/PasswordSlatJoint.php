<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\PasswordControl;
use SetBased\Html\Html;

class PasswordSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct( $theHeaderText )
  {
<<<<<<< HEAD
    $this->myDataType   = 'control-password';
=======
    $this->myDataType   = 'control_password';
>>>>>>> c200cb9d405dd6e25de8a09d2ca068cd4ca4966f
    $this->myHeaderHtml = Html::txt2Html( $theHeaderText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a password form control.
   *
   * @param string $theName The local name of the password form control.
   *
   * @return PasswordControl
   */
  public function createCell( $theName )
  {
    return new PasswordControl($theName);
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
