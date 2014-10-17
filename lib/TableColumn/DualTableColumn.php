<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * @brief Abstract class for defining classes for generating HTML code for two columns of OverviewTable with a
 *        single column header.
 */
abstract class DualTableColumn extends TableColumn
{

  /**
   * The type of the data that second column holds.
   *
   * @var string
   */
  protected $myDataType2;

  /**
   * The sort direction of the data in the second column.
   *
   * @var string|null
   */
  protected $mySortDirection2;

  /**
   * If set the data in the table of the second column is sorted or must be sorted by this column (and possible by other
   * columns) and its value determines the order in which the data of the table is sorted.
   *
   * @var int
   */
  protected $mySortOrder2;

  /**
   * If set second column can be used for sorting the data in the table of this column.
   *
   * @var bool
   */
  protected $mySortable2 = true;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the number of columns spanned, i.e. 2.
   *
   * @return int
   */
  public function getColSpan()
  {
    return 2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for col element for this table columns.
   *
   * @return string
   */
  public function getHtmlColumn()
  {
    // Add class indicating the type of data of the first column.
    if ($this->myDataType)
    {
      $class1 = 'data-type-'.$this->myDataType;
    }
    else
    {
      $class1 = null;
    }

    // Add class indicating the type of data of the second column.
    if ($this->myDataType2)
    {
      $class2 = 'data-type-'.$this->myDataType2;
    }
    else
    {
      $class2 = null;
    }

    return '<col'.Html::generateAttribute( 'class', $class1 ).'/><col'.Html::generateAttribute( 'class', $class2 ).'/>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing @a th tags) for filtering this table columns.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    $ret = '<td><input type="text"/></td>';
    $ret .= '<td><input type="text"/></td>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing @a th tags) for the header of this table columns.
   *
   * @return string
   */
  public function getHtmlColumnHeader()
  {
    $class = '';

    // Add class indicating this column can be used for sorting.
    if ($this->mySortable)
    {
      if ($class) $class .= ' ';
      $class .= 'sort-1';
    }

    // Add class indicating the sort order of this column.
    if ($this->mySortable && $this->mySortDirection)
    {
      if ($class) $class .= ' ';

      // Add class indicating this column can be used for sorting.
      $class .= 'sort-order-1-';
      $class .= $this->mySortOrder;

      $class .= ($this->mySortDirection=='desc') ? ' sorted-1-desc' : ' sorted-1-asc';
    }

    // Add class indicating this column can be used for sorting.
    if ($this->mySortable2)
    {
      if ($class) $class .= ' ';
      $class .= 'sort-2';

      // Add class indicating the sort order of this column.
      if ($this->mySortOrder2)
      {
        if ($class) $class .= ' ';

        // Add class indicating this column can be used for sorting.
        $class .= 'sort-order-2-';
        $class .= $this->mySortOrder2;

        $class .= ($this->mySortDirection2=='desc') ? ' sorted-2-desc' : ' sorted-2-asc';
      }
    }

    return '<th colspan="2" class="'.$class.'"><span>&nbsp;</span>'.Html::txt2Html( $this->myHeaderText ).'</th>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the first and the second column as not sortable (overriding the default behaviour a child class).
   */
  public function notSortable()
  {
    $this->mySortable  = false;
    $this->mySortable2 = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the first column as not sortable (overriding the default behaviour a child class).
   */
  public function notSortable1()
  {
    $this->mySortable = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the second column as not sortable (overriding the default behaviour a child class).
   */
  public function notSortable2()
  {
    $this->mySortable2 = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the sorting order of the first column to @a $theSortOrder. If @a $theDescendingFlag is set the data in this
   * column is sorted descending, otherwise ascending.
   *
   * @param int  $theSortOrder
   * @param bool $theDescendingFlag
   */
  public function sortOrder1( $theSortOrder, $theDescendingFlag = false )
  {
    $this->mySortDirection = ($theDescendingFlag) ? 'desc' : 'asc';
    $this->mySortOrder     = $theSortOrder;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the sorting order of the left column to @a $theSortOrder. If @a $theDescendingFlag is set the data in this
   * column is sorted descending, otherwise ascending.
   *
   * @param int  $theSortOrder
   * @param bool $theDescendingFlag
   */
  public function sortOrder2( $theSortOrder, $theDescendingFlag = false )
  {
    $this->mySortDirection2 = ($theDescendingFlag) ? 'desc' : 'asc';
    $this->mySortOrder2     = $theSortOrder;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
