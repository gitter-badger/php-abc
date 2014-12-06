<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\TableRow;

use SetBased\Html\Html;
use SetBased\Html\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
class DateTimeTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default format of the date-time if the format specifier is omitted in the constructor.
   *
   * @var string
   */
  public static $ourDefaultFormat = 'd-m-Y H:i:s';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a datetime value to a detail table.
   *
   * @param DetailTable $theTable  The (detail) table.
   * @param string      $theHeader The row header text.
   * @param string      $theValue  The datetime in Y-m-d H:i:s format.
   * @param string      $theFormat The formatting string (see DateTime::format).
   */
  public static function addRow( $theTable, $theHeader, $theValue, $theFormat = null )
  {
    $row = '<tr>';

    $row .= '<th>';
    $row .= Html::txt2Html( $theHeader );
    $row .= '</th>';

    $date = \DateTime::createFromFormat( 'Y-m-d', $theValue );

    if ($date)
    {
      // The $theValue is a valid date.
      $format = ($theFormat) ? $theFormat : self::$ourDefaultFormat;
      $row .= '<td class="date" data-value="'.$date->format( 'Y-m-d' ).'">'.
        Html::txt2Html( $date->format( $format ) ).
        '</td>';
    }
    else
    {
      // The $theValue is not a valid datetime.
      $row .= '<td>'.Html::txt2Html( $theValue ).'</td>';
    }

    $row .= '</tr>';

    $theTable->addRow( $row );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
