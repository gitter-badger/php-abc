<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\SlatJoint;

use SetBased\Abc\Form\Control\ConstantControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat joint for table columns witch table cells with a constant form controls.
 */
class ConstantSlatJoint extends SlatJoint
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a constant form control.
   *
   * @param string $theName The local name of the constant form control.
   *
   * @return ConstantControl
   */
  public function createCell($theName)
  {
    return new ConstantControl($theName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A constant control must never be shown in a table. Hence it spans 0 columns.
   *
   * @return int Always 0
   */
  public function getColSpan()
  {
    return 0;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A constant control must never be shown in a table. Hence it it has no column.
   *
   * @return string Always empty.
   */
  public function getHtmlColumn()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A constant control must never be shown in a table. Hence filter must never be shown too.
   *
   * @return string Empty string
   */
  public function getHtmlColumnFilter()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A constant control must never be shown in a table. Hence header must never be shown too.
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
