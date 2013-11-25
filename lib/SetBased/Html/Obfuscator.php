<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Interface Obfuscator
 *
 * Interface for defining classes for obfuscating and un-obfuscating database ID.
 *
 * @package SetBased\Html
 */
interface Obfuscator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the obfuscated value of @a $theValue.
   *
   * @param string|null $theValue
   *
   * @return string|null
   */
  public function encode( $theValue );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the un-obfuscated value of @a $theCode.
   *
   * @param string|null $theCode
   *
   * @return string|null
   */
  public function decode( $theCode );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
