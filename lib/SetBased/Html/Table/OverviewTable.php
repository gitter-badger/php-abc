<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Table;

use SetBased\Html\Html;

class OverviewTable
{
  /**
   * The attributes of the table tag of this table.
   *
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * The objects for generating the columns of this table.
   *
   * @var \SetBased\Html\TableColumn\TableColumn[]
   */
  protected $myColumns = array();

  /**
   * If set to true the header will contain a row for filtering.
   *
   * @var bool
   */
  protected $myFilter = false;

  /**
   * The index in $myColumns of the next column added to this table.
   *
   * @var int
   */
  private $myColIndex = 1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds @a $theColumn to this table and returns the column.
   *
   * @param \SetBased\Html\TableColumn\TableColumn $theColumn
   *
   * @return \SetBased\Html\TableColumn\TableColumn
   */
  public function addColumn( $theColumn )
  {
    // Add the column to our array of columns.
    $this->myColumns[$this->myColIndex] = $theColumn;

    $theColumn->onAddColumn( $this, $this->myColIndex );

    // Increase the index for the next added column.
    $this->myColIndex += $theColumn->getColSpan();

    return $theColumn;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disables table filtering.
   */
  public function disableFilter()
  {
    $this->myFilter = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables table filtering.
   */
  public function enableFilter()
  {
    $this->myFilter = true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of this table displaying @a $theRows as data.
   *
   * @param array $theRows
   *
   * @return string
   */
  public function getHtmlTable( &$theRows )
  {
    $ret = $this->getHtmlPrefix();

    $ret .= '<table';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    // Generate HTML code for the table header.
    $ret .= "<thead>\n";
    $ret .= $this->getHtmlHeader();
    $ret .= "</thead>\n";


    // Generate HTML code for the table body.
    $ret .= "<tbody>\n";
    $ret .= $this->getHtmlBody( $theRows );
    $ret .= "</tbody>\n";

    $ret .= "</table>\n";

    $ret .= $this->getHtmlPostfix();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the total number of columns of this table.
   *
   * @return int
   */
  public function getNumberOfColumns()
  {
    return $this->myColIndex - 1;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the body for this table holding @a $theRows as data.
   *
   * @param array $theRows
   *
   * @return string
   */
  protected function getHtmlBody( &$theRows )
  {
    $ret = '';
    $i   = 1;
    foreach ($theRows as $row)
    {
      if ($i % 2==0) $ret .= "<tr class='even'>\n";
      else           $ret .= "<tr class='odd'>\n";
      foreach ($this->myColumns as $column)
      {
        $ret .= $column->getHtmlCell( $row );
      }
      $ret .= "</tr>\n";
      $i++;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the header for this table.
   *
   * @return string
   */
  protected function getHtmlHeader()
  {
    $ret = "<tr class='header'>\n";
    foreach ($this->myColumns as $column)
    {
      $ret .= $column->getHtmlColumnHeader();
    }
    $ret .= "</tr>\n";

    if ($this->myFilter)
    {
      $ret .= "<tr class='filter'>\n";
      foreach ($this->myColumns as $column)
      {
        $ret .= $column->getHtmlColumnFilter();
      }
      $ret .= "</tr>\n";
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code inserted before the HTML code of the table.
   *
   * @return string
   */
  protected function getHtmlPostfix()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code appended after the HTML code of the table.
   *
   * @return string
   */
  protected function getHtmlPrefix()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute @a $theName of this table to @a $theValue.
   * * If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
   * * If @a $theName is 'class' the @a $theValue is appended to space separated list of classes (unless the above rule
   *   applies.)
   *
   * @param $theName  string      The name of the attribute.
   * @param $theValue string|null The value for the attribute.
   */
  protected function setAttribute( $theName, $theValue )
  {
    if ($theValue===null || $theValue===false || $theValue==='')
    {
      unset($this->myAttributes[$theName]);
    }
    else
    {
      if ($theName==='class' && isset($this->myAttributes[$theName]))
      {
        $this->myAttributes[$theName] .= ' ';
        $this->myAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


