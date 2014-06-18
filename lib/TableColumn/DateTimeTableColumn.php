<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableColumn;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class DateTimeTableColumn extends TableColumn
{
  /**
   * The default format of the date-time if the format specifier is omitted in the constructor.
   *
   * @var string
   */
  public static $ourDefaultFormat = 'd-m-Y H:i:s';

  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $myFieldName;

  /**
   * The format specifier for formatting the content of this table column.
   *
   * @var string
   */
  protected $myFormat;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theHeaderText The header text of this table column.
   * @param string $theFieldName  The field name of the data row used for generating this table column.
   * @param string $theFormat     The format specifier for formatting the content of this table column.
   */
  public function __construct( $theHeaderText, $theFieldName, $theFormat = null )
  {
    $this->myDataType   = 'datetime';
    $this->myHeaderText = $theHeaderText;
    $this->myFieldName  = $theFieldName;
    $this->myFormat     = ($theFormat) ? $theFormat : self::$ourDefaultFormat;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function getHtmlCell( $theData )
  {
    $datetime = \DateTime::createFromFormat( 'Y-m-d H:i:s', $theData[$this->myFieldName] );

    if ($datetime)
    {
      $class = 'datetime data-'.urlencode( $datetime->format( 'Y-m-d H:i:s' ) );

      return '<td class="'.$class.'">'.Html::txt2Html( $datetime->format( $this->myFormat ) ).'</td>';
    }
    else
    {
      return '<td class="datetime"></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
