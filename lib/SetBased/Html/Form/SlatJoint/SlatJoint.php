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
   * The type of the data that this table row/column hol ds.
   *
   * @var string
   */
  protected $myDataType;

  /**
   * The header text of this row/column.
   *
   * @var string
   */
  protected $myHeaderText;

  /**
   * If set this column can be used for sorting the data in the table of this column.
   *
   * @var bool
   */
  protected $mySortable = true;

  /**
   * If set this column can be used for sorting data of the table of this column.
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

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName The name of the form control in the table cell.
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckboxesControl|RadiosControl
   */
  abstract public function createCell( $theName );

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

    return "<th class='$class'>".Html::txt2Html( $this->myHeaderText )."</th>\n";
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
