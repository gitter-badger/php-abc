<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * @brief Abstract class for generation HTML code for tables either in a column or row context.
 */
abstract class TableColumn
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
  abstract public function getHtmlCell( &$theData );

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
  /**
   * Returns HTML code (including opening and closing th tags) for the table header cell.
   *
   * @return string
   */
  public function getHtmlRowHeader()
  {
    return "<th>".Html::txt2Html( $this->myHeaderText )."</th>\n";
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
   * Set the sorting order of this column to @a $theSortOrder. If @a $theDescendingFlag is set the data in this column
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
