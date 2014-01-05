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
   * If set to true the header will contain a row for filtering.
   *
   * @var bool
   */
  protected $myFilter = false;

  /**
   * The slat joints for the louver control of this slat control factory.
   *
   * @var SlatJoint[]
   */
  protected $mySlatJoints;

  /**
   * The index in $mySlatJoints of the next slat joint added to this slat control factory.
   *
   * @var int
   */
  private $myColIndex = 1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a slat joint (i.e. a column to the form) to this slat control factory and returns this slat joint.
   *
   * @param string    $theSlatJointName The name of the slat joint.
   * @param SlatJoint $theSlatJoint     The slat joint.
   *
   * @return SlatJoint
   */
  public function addSlatJoint( $theSlatJointName, $theSlatJoint )
  {
    $this->mySlatJoints[$theSlatJointName] = $theSlatJoint;

    return $theSlatJoint;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form control using a slat joint and returns the created form control.
   *
   * @param ComplexControl $theParentControl The parent control
   * @param string         $theSlatJointName The name of the slat joint.
   * @param string|null    $theControlName   The name of the created form control. If null the form control will have
   *                                         the same name as the slat joint. Use '' for an empty name (should only be
   *                                         used if the created form control is a complex form control).
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckBoxesControl|RadiosControl
   */
  public function createFormControl( $theParentControl, $theSlatJointName, $theControlName = null )
  {
    $control = $this->mySlatJoints[$theSlatJointName]->createCell( isset($theControlName) ?
                                                                     $theControlName : $theSlatJointName );
    $theParentControl->addFormControl( $control );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form controls of a slat in a louver control.
   *
   * @param LouverControl $theLouverControl The louver control.
   * @param array         $theData An array from the nested arrays as set in LouverControl::setData.
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
