<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Obfuscator;

use InvalidArgumentException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Factory for obfuscators for development only.
 */
class DevelopmentObfuscatorFactory implements ObfuscatorFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public static function decode($theCode, $theLabel)
  {
    if ($theCode===null || $theCode===false || $theCode==='') return null;

    if (substr($theCode, 0, strlen($theLabel))!=$theLabel)
    {
      throw new InvalidArgumentException(sprintf("Labels '%s' and '%' don't match.",
                                                 substr($theCode, 0, strlen($theLabel)),
                                                 $theLabel));
    }

    return substr($theCode, strlen($theLabel) + 1);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public static function encode($theId, $theLabel)
  {
    if ($theId===null || $theId===false || $theId==='') return null;

    return $theLabel.'_'.$theId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * @return DevelopmentObfuscator
   */
  public static function getObfuscator($theLabel)
  {
    return new DevelopmentObfuscator($theLabel);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
