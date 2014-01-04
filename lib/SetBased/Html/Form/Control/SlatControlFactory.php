<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Form\SlatJoint\SlatJoint;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SlatControlFactory
 *
 * @package SetBased\Html\Form\TableRow
 */
abstract class SlatControlFactory
{
  /**
   * The slat joints for the louver control of this slat control factory.
   *
   * @var SlatJoint[]
   */
  protected $mySlatJoints;

  /**
   * If set to true the header will contain a row for filtering.
   *
   * @var bool
   */
  protected $myFilter = false;

  /**
   * The index in $mySlatJoints of the next slat joint added to this slat control factory.
   *
   * @var int
   */
  private $myColIndex = 1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param LouverControl $theLouverControl
   * @param array         $theData
   */
  abstract public function createRow( $theLouverControl, $theData );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disables filtering.
   */
  public function disableFilter()
  {
    $this->myFilter = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables filtering.
   */
  public function enableFilter()
  {
    $this->myFilter = true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the thead element of the table form control.
   *
   * @return string
   */
  public function getHtmlHeader()
  {
    $ret = "<tr class='header'>\n";
    foreach ($this->mySlatJoints as $factory)
    {
      $ret .= $factory->getHtmlColumnHeader();
    }
    $ret .= "</tr>\n";

    if ($this->myFilter)
    {
      $ret .= "<tr class='filter'>\n";
      foreach ($this->mySlatJoints as $factory)
      {
        $ret .= $factory->getHtmlColumnFilter();
      }
      $ret .= "</tr>\n";
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the total number of columns of the table form control.
   *
   * @return int
   */
  public function getNumberOfColumns()
  {
    return $this->myColIndex - 1;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
