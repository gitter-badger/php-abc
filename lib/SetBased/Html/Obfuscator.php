<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 * @par Copyright:
 * Set Based IT Consultancy
 * $Date: 2013/03/04 19:02:37 $
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Interface for defining classes for obfuscation and deobfuscating database ID.
 */
interface Obfuscator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the obfuscate value of @a $theValue.
   */
  public function encode( $theValue );

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the deobfuscate value of @a $theCode.
   */
  public function decode( $theCode );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
