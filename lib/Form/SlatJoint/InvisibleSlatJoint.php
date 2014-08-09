<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\InvisibleControl;

class InvisibleSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a invisible form control.
   *
   * @param string $theName The local name of the invisible form control.
   *
   * @return InvisibleControl
   */
  public function createCell( $theName )
  {
    return new InvisibleControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A invisible control must never be shown in a table. Hence it spans 0 columns.
   *
   * @return int Always 0
   */
  public function getColSpan()
  {
    return 0;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A invisible control must never be shown in a table. Hence it it has no column.
   *
   * @return string Always empty.
   */
  public function getHtmlColumn()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A invisible control must never be shown in a table. Hence filter must never be shown too.
   *
   * @return string Empty string
   */
  public function getHtmlColumnFilter()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A invisible control must never be shown in a table. Hence header must never be shown too.
   *
   * @return string Empty string
   */
  public function getHtmlColumnHeader()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
