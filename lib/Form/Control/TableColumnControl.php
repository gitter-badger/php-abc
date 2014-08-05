<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\TableColumn\TableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SpanControl
 *
 * @package SetBased\Html\Form\Control
 */
class TableColumnControl extends Control
{
  /**
   * @var array
   */
  protected $myRow;

  /**
   * @var TableColumn
   */
  protected $myTableColumn;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlTableCell( $theParentName )
  {
    return $this->myTableColumn->getHtmlCell( $this->myRow );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null;
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the table column of this form control.
   *
   * @param TableColumn $theTableColumn
   */
  public function setTableColumn( $theTableColumn )
  {
    $this->myTableColumn = $theTableColumn;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValue( $theRow )
  {
    $this->myRow = $theRow;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param mixed $theValues
   */
  public function setValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
