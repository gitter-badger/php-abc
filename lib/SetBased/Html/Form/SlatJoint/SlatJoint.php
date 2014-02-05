<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\SlatJoint;

use SetBased\Html\Form\Control\CheckboxesControl;
use SetBased\Html\Form\Control\ComplexControl;
use SetBased\Html\Form\Control\RadiosControl;
use SetBased\Html\Form\Control\SelectControl;
use SetBased\Html\Form\Control\SimpleControl;
use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SlatJoint
 *
 * @package SetBased\Html\Form\SlatJoint
 */
abstract class SlatJoint
{
  /**
   * The type of the data that the column of this slat joint holds.
   *
   * @var string
   */
  protected $myDataType;

  /**
   * The inner HTML code of the th element of the column header of this slat joint.
   *
   * @var string
   */
  protected $myHeaderHtml;

  /**
   * The sort direction of the data in the column of this slat joint.
   *
   * @var string
   */
  protected $mySortDirection;

  /**
   * If set the data in the table of this column is sorted or must be sorted by this column (and possible by other
   * columns) and its value determines the order in which the data of the table is sorted.
   *
   * @var int
   */
  protected $mySortOrder;

  /**
   * If set this column can be used for sorting the data in the table of this column.
   *
   * @var bool
   */
  protected $mySortable = true;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName The name of the form control in the table cell.
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckboxesControl|RadiosControl
   */
  abstract public function createCell( $theName );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the number of columns spanned by this object.
   *
   * @return int
   */
  public function getColSpan()
  {
    return 1;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  abstract public function getHtmlColumnFilter();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table header cell.
   *
   * @return string
   */
  public function getHtmlColumnHeader()
  {
    $class = '';

    // Add class indicating the type of data of this column.
    if ($this->myDataType)
    {
      $class .= 'data-type-';
      $class .= $this->myDataType;
    }

    // Add class indicating this column can be used for sorting.
    if ($this->mySortable)
    {
      if ($class) $class .= ' ';
      $class .= 'sort';
    }

    // Add class indicating the sort order of this column.
    if ($this->mySortable && $this->mySortDirection)
    {
      if ($class) $class .= ' ';

      // Add class indicating this column can be used for sorting.
      $class .= 'sort sort-order-';
      $class .= $this->mySortOrder;

      if ($this->mySortDirection=='asc')
      {
        $class .= ' sorted-asc';
      }
      else
      {
        $class .= 'd sorted-desc';
      }
    }

    return "<th class='$class'>".$this->myHeaderHtml."</th>\n";
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML code of the th element of the column header of this slat joint.
   *
   * @param string $theHtml The HTML code.
   */
  public function setHeaderHtml( $theHtml )
  {
    $this->myHeaderHtml = $theHtml;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the sort order of the data in the column of this slat join.
   *
   * @param int $theSortOrder The sort order.
   */
  public function setSortOrder( $theSortOrder )
  {
    $this->mySortOrder = $theSortOrder;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the sort direction of the data in the column of this slat join.
   *
   * @param int $theSortDirection The sort direction.
   *                              * 'desc': Sort direction is descending.
   *                              * Otherwise: Sort direction is ascending.
   */
  public function setSortDirection( $theSortDirection )
  {
    $this->mySortDirection = ($theSortDirection=='desc') ? 'desc' : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the header text for the column header of this slat joint.
   *
   * @param string $theText The header text (applicable characters will be converted to HTML entities).
   */
  public function setHeaderText( $theText )
  {
    $this->myHeaderHtml = Html::txt2Html( $theText );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
