<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for obfuscator database ID using two very simple encryption techniques: a (very weak) encryption method and a
 * bit mask with the same length as the database ID.
 */
class ReferenceObfuscator implements Obfuscator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Some magic constants.
   */
  const C1 = 52845;
  const C2 = 22719;

  /**
   * The bit mask to be applied on a database ID. The length (in bytes) of this bit mask must be equal to the maximum
   * length (in bytes) of the database ID.
   *
   * @var int
   */
  private $myBitMask;

  /**
   * The key in the encryption algorithm. Must be a number between 0 and 65535.
   *
   * @var int
   */
  private $myKey;

  /**
   * The maximum length (in bytes) of the database ID.
   *
   * @var int
   */
  private $myLength;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theKey     The key used by the encryption algorithm. Must be a number between 0 and 65535.
   * @param int $theLength  The maximum length (in bytes) of the database ID.
   * @param int $theBitMask The bit mask to be applied on a database ID. The length (in bytes) of this bit mask must be
   *                        equal to the maximum length (in bytes) of the database ID.
   */
  public function __construct($theLength, $theKey, $theBitMask)
  {
    $this->myLength  = $theLength;
    $this->myKey     = $theKey;
    $this->myBitMask = $theBitMask;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * De-obfuscates a obfuscated database ID.
   *
   * @param string $theCode   The obfuscated database ID.
   * @param int    $theLength The length (in bytes) of the (original) database ID.
   * @param int    $theKey    The encryption key. Must be a number between 0 and 65535.
   * @param int    $theMask   The bit mask. The length (in bytes) of this bit mask must be equal to the maximum length
   *                          (in bytes) of the database ID.
   *
   * @return int|null
   */
  public static function decrypt($theCode, $theLength, $theKey, $theMask)
  {
    if ($theCode===null || $theCode===false || $theCode==='') return null;

    $result = 0;
    $val    = hexdec($theCode);
    $k      = 1;
    for ($i = 0; $i<$theLength; $i++)
    {
      $remainder = $val % 256;
      $part      = $remainder ^ ($theKey >> 8);
      $result    = $result + $k * $part;
      $k         = $k << 8;
      $theKey    = (($remainder + $theKey) * self::C1 + self::C2) % 65536;
      $val       = $val >> 8;
    }

    return $result ^ $theMask;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Obfuscates a database ID.
   *
   * @param int $theId     The database ID.
   * @param int $theLength The length (in bytes) of the (original) database ID.
   * @param int $theKey    The encryption key. Must be a number between 0 and 65535.
   * @param int $theMask   The bit mask. The length (in bytes) of this bit mask must be equal to the maximum length (in
   *                       bytes) of the database ID.
   *
   * @return null|string
   */
  public static function encrypt($theId, $theLength, $theKey, $theMask)
  {
    if ($theId===null || $theId===false || $theId==='') return null;

    $result = 0;
    $val    = $theId ^ $theMask;
    $k      = 1;
    for ($i = 0; $i<$theLength; $i++)
    {
      $remainder = $val % 256;
      $part      = $remainder ^ ($theKey >> 8);
      $result    = $result + $k * $part;
      $k         = $k << 8;
      $theKey    = (($part + $theKey) * self::C1 + self::C2) % 65536;
      $val       = $val >> 8;
    }

    return dechex($result);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function decode($theCode)
  {
    return self::decrypt($theCode, $this->myLength, $this->myKey, $this->myBitMask);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function encode($theId)
  {
    return self::encrypt($theId, $this->myLength, $this->myKey, $this->myBitMask);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
