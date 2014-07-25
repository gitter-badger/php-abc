<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * @brief Abstract class for generation HTML code for tables either in a column context.
 */
abstract class TableColumn
{
  /**
   * The type of the data that this table column holds.
   *
   * @var string
   */
  protected $myDataType;

  /**
   * The header text of this column.
   *
   * @var string
   */
  protected $myHeaderText;

  /**
   * The sort direction of the data in this column.
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
   * Returns HTML code (including opening and closing td tags) for the table cell.
   *
   * @param array $theData
   *
   * @return string
   */
  abstract public function getHtmlCell( $theData );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for col element for this table column.
   *
   * @return string
   */
  public function getHtmlColumn()
  {
    // Add class indicating the type of data of this column.
    if ($this->myDataType)
    {
      $class = 'data-type-'.$this->myDataType;
    }
    else
    {
      $class = null;
    }

    return '<col'.Html::generateAttribute( 'class', $class ).'/>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the data type of this table column.
   *
   * @return string
   */
  public function getDataType()
  {
    return $this->myDataType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table filter cell.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    if ($this->myHeaderText===null)
    {
      // If the column header is empty there is no column filter by default. This behaviour can be overridden in a
      // child class.
      return '<td></td>';
    }
    else
    {
      // The default filter is a simple text filter.
      return '<td><input type="text"/></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code (including opening and closing th tags) for the table header cell.
   *
   * @return string
   */
  public function getHtmlColumnHeader()
  {
    if ($this->myHeaderText===null)
    {
      $class = 'empty';
    }
    else
    {
      if ($this->mySortable)
      {
        // Add class indicating this column can be used for sorting.
        $class = 'sort';

        // Add class indicating the sort order of this column.
        if ($this->mySortOrder)
        {
          if ($class) $class .= ' ';

          // Add class indicating this column can be used for sorting.
          $class .= 'sort-order-';
          $class .= $this->mySortOrder;

          $class .= ($this->mySortDirection=='desc') ? ' sorted-desc' : ' sorted-asc';
        }
      }
      else
      {
        $class = null;
      }
    }

    return '<th'.Html::generateAttribute( 'class', $class ).'>'.Html::txt2Html( $this->myHeaderText ).'</th>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if this column has no header text.
   *
   * @return bool
   */
  public function hasEmptyHeader()
  {
    return !isset($this->myHeaderText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If this columns is sortable sets this column as not sortable (overriding the default behaviour a child class).
   * Has no effect when this column is not sortable.
   */
  public function notSortable()
  {
    $this->mySortable = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * This function is called by @c OverviewTable::addColumn.
   */
  public function onAddColumn( $theTable, $theColumnIndex )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the sorting order of this column to @a $theSortOrder. If @a $theDescendingFlag is set the data in this column
   * is sorted descending, otherwise ascending.
   *
   * @param int  $theSortOrder
   * @param bool $theDescendingFlag
   */
  public function sortOrder( $theSortOrder, $theDescendingFlag = false )
  {
    $this->mySortDirection = ($theDescendingFlag) ? 'desc' : 'asc';
    $this->mySortOrder     = $theSortOrder;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
