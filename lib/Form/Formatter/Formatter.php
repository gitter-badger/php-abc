<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Formatter;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Interface for defining classes for formatting values from machine values the human readable values.
 */
interface Formatter
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the human readable value of a machine value.
   *
   * @param mixed $theValue The machine value.
   */
  public function format($theValue);
}

//----------------------------------------------------------------------------------------------------------------------
