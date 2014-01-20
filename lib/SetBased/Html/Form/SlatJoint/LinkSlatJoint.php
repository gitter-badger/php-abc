<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\LinkControl;
use SetBased\Html\Html;

class LinkSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   */
  public function __construct( $theHeaderText )
  {
    $this->myDataType   = ' control_link';
    $this->myHeaderHtml = Html::txt2Html( $theHeaderText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a link form control.
   *
   * @param string $theName The local name of the link form control.
   *
   * @return LinkControl
   */
  public function createCell( $theName )
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
    return "<td><input type='text'/></td>\n";
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
