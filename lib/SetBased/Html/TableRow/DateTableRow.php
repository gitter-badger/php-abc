<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class DateTableRow extends TableRow
{
  /**
   * The default format of the date if the format specifier is omitted in the constructor.
   *
   * @var string
   */
  public static $ourDefaultFormat = 'd-m-Y';

  /**
   * Many (database) system use a certain value for empty dates or open end dates. Such a value must be shown as an
   * empty table cell.
   *
   * @var string
   */
  public static $ourOpenDate = '9999-12-31';

  /**
   * The field name of the data row used for generating this table row.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * The format specifier for formatting the content of this table row.
   *
   * @var string
   */
  protected $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text for this row.
   * @param string $theFieldName  The field name of the data row used for generating this table row.
   * @param string $theFormat     The format specifier for formatting the content of this table row.
   */
  public function __construct( $theHeaderText, $theFieldName, $theFormat = null )
  {
    $this->myDataType   = 'date';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myFormat     = ($theFormat) ? $theFormat : self::$ourDefaultFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( &$theData )
  {
    if ($theData[$this->myFieldName] && $theData[$this->myFieldName]!=self::$ourOpenDate)
    {
      $date = \DateTime::createFromFormat( 'Y-m-d', $theData[$this->myFieldName] );

      if ($date)
      {
        $class  = 'date data-';
        $class .= urlencode( $date->format( 'Y-m-d' ) );

        return "<td class='$class'>".Html::txt2Html( $date->format( $this->myFormat ) )."</td>\n";
      }
      else
      {
        // The $theData[$this->myFieldName] is not a valid date.
        return "<td>".Html::txt2Html( $theData[$this->myFieldName] )."</td>\n";
      }
    }
    else
    {
      return "<td class='date'></td>\n";
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
