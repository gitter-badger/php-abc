<?php //----------------------------------------------------------------------------------------------------------------------
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
   * If set the second column can be used for sorting data of the table of this column.
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
   * Returns HTML code (including opening and closing @a th tags) for filtering this table columns.
   *
   * @return string
   */
  public function getHtmlColumnFilter()
  {
    $ret = "<td><input type='text'/></td>\n";
    $ret .= "<td><input type='text'/></td>\n";

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

    // Add class indicating the type of data of this column.
    if ($this->myDataType)
    {
      $class .= 'data-type-1-';
      $class .= $this->myDataType;
    }

    // Add class indicating the sort order of this column.
    if ($this->mySortDirection)
    {
      if ($class) $class .= ' ';

      // Add class indicating this column can be used for sorting.
      $class .= 'sort-1 sort-order-';
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

    // Add class indicating the type of data of this column.
    if ($this->myDataType2)
    {
      $class .= 'data-type-2-';
      $class .= $this->myDataType2;
    }

    // Add class indicating the sort order of this column.
    if ($this->mySortDirection2)
    {
      if ($class) $class .= ' ';

      // Add class indicating this column can be used for sorting.
      $class .= 'sort-2 sort-order-2';
      $class .= $this->mySortOrder2;

      if ($this->mySortDirection2=='asc')
      {
        $class .= ' sorted-asc';
      }
      else
      {
        $class .= 'd sorted-desc';
      }
    }

    return "<th colspan='2' class='$class'><span>&nbsp;</span>".Html::txt2Html( $this->myHeaderText )."</th>\n";
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

