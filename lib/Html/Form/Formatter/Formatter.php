<?php
//----------------------------------------------------------------------------------------------------------------------
/**
 * @author Paul Water
 * @par    Copyright:
 * Set Based IT Consultancy
 * $Date: 2013/03/04 19:02:37 $
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Formatter;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Interface Formatter
 * Interface for defining classes for formatting values from machine values the human readable values.
 *
 * @package SetBased\Html\Form\Cleaner
 */
interface Formatter
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the formatted value of @a $theValue.
   *
   * @param mixed $theValue
   */
  public function format( $theValue );
}

//----------------------------------------------------------------------------------------------------------------------
