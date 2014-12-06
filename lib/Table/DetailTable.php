<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Table;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class DetailTable
 *
 * @package SetBased\Html\Table
 */
class DetailTable
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The attributes of the table tag of this table.
   *
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * The HTML snippet with all rows of this table.
   *
   * @var string
   */
  protected $myRows;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row to this table.
   *
   * @param string $theRow An HTML snippet with a table row.
   */
  public function addRow( $theRow )
  {
    $this->myRows .= $theRow;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of this table.
   *
   * @return string
   */
  public function getHtmlTable()
  {
    $ret = $this->getHtmlPrefix();

    $ret .= '<table';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';

    // Generate HTML code for the table header.
    $ret .= '<thead>';
    $ret .= $this->getHtmlHeader();
    $ret .= '</thead>';


    // Generate HTML code for the table header.
    $ret .= '<tfoot>';
    $ret .= $this->getHtmlFooter();
    $ret .= '</tfoot>';

    // Generate HTML code for the table body.
    $ret .= '<tbody>';
    $ret .= $this->myRows;
    $ret .= '</tbody>';

    $ret .= '</table>';

    $ret .= $this->getHtmlPostfix();

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
  public function setAttribute( $theName, $theValue )
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
