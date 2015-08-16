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
    $value = $theData[$this->myFieldName];

    if ($value!==false && $value!==null && $value!=='')
    {
      $datetime = \DateTime::createFromFormat( 'Y-m-d H:i:s', $theData[$this->myFieldName] );

      if ($datetime)
      {
        $cell = '<td class="datetime" data-value="';
        $cell .= $datetime->format( 'Y-m-d H:i:s' );
        $cell .= '">';
        $cell .= Html::txt2Html( $datetime->format( $this->myFormat ) );
        $cell .= '</td>';

        return $cell;
      }
      else
      {
        // The value is not a valid datetime.
        return '<td>'.Html::txt2Html( $theData[$this->myFieldName] ).'</td>';
      }
    }
    else
    {
      // The value is an empty datetime.
      return '<td class="datetime"></td>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
