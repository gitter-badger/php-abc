<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Table;

use SetBased\Html\Html;

class DetailTable
{
  /**
   * The attributes of the table tag of this table.
   *
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * The objects for generating the rows of this table.
   *
   * @var \SetBased\Html\TableRow\TableRow[]
   */
  protected $myRows = array();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds @a $theRow to this table and returns the row.
   *
   * @param \SetBased\Html\TableRow\TableRow $theRow
   *
   * @return \SetBased\Html\TableRow\TableRow
   */
  public function addRow( $theRow )
  {
    // Add the column to our array of rows.
    $this->myRows[] = $theRow;

    return $theRow;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of this table displaying @a $theData.
   *
   * @param array $theData
   *
   * @return string
   */
  public function getHtmlTable( &$theData )
  {
    $ret = '<table';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    // Generate HTML code for the table header.
    $ret .= "<thead>\n";
    $ret .= $this->getHtmlHeader();
    $ret .= "</thead>\n";


    // Generate HTML code for the table header.
    $ret .= "<tfoot>\n";
    $ret .= $this->getHtmlFooter();
    $ret .= "</tfoot>\n";

    // Generate HTML code for the table body.
    $ret .= "<tbody>\n";
    $ret .= $this->getHtmlBody( $theData );
    $ret .= "</tbody>\n";

    $ret .= "</table>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the body for this table holding @a $theData as data.
   *
   * @param array $theData The data to be shown in this detail table.
   *
   * @return string
   */
  protected function getHtmlBody( &$theData )
  {
    $ret = '';

    foreach ($this->myRows as $row)
    {
      $ret .= "<tr>\n";
      $ret .= $row->getHtmlRowHeader();
      $ret .= $row->getHtmlCell( $theData );
      $ret .= "</tr>\n";
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the footer for this table.
   *
   * @return string
   */
  protected function getHtmlFooter()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the header for this table.
   *
   * @return string
   */
  protected function getHtmlHeader()
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


