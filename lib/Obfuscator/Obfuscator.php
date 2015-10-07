<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Interface for defining classes for obfuscating and un-obfuscating database IDs.
 */
interface Obfuscator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the obfuscated value of a database ID.
   *
   * @param int|null $theId The database ID.
   *
   * @return string|null The obfuscated value.
   */
  public function encode($theId);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the un-obfuscated ID a obfuscated database ID.
   *
   * @param string|null $theCode The obfuscated value.
   *
   * @return int|null The database ID.
   */
  public function decode($theCode);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
