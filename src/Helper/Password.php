<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Helper;

use SetBased\Affirm\Affirm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A helper class for hashing and verifying passwords.
 */
class Password
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The algorithmic cost that should be used in password_hash.
   *
   * @var int
   */
  public static $ourCost = 14;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a hashed password using PHP native password_hash function.
   *
   * @param string $thePassword
   *
   * @return string The hashed password.
   */
  public static function passwordHash($thePassword)
  {
    $options = ['cost' => self::$ourCost];

    $hash = password_hash($thePassword, PASSWORD_DEFAULT, $options);
    if ($hash===false) Affirm::assertFailed('Function password_hash failed');

    return $hash;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Checks if the given hash matches the given options using PHP native password_needs_rehash function.
   *
   * @param string $theHash The hash (as stored in the database).
   *
   * @return bool True if and only if the password matches with the hash value.
   */
  public static function passwordNeedsRehash($theHash)
  {
    $options = ['cost' => self::$ourCost];

    return password_needs_rehash($theHash, PASSWORD_DEFAULT, $options);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Verifies that a password matches a hash using PHP native password_verify function.
   *
   * @param string $thePassword The password (as given by the user).
   * @param string $theHash     The hash (as stored in the database).
   *
   * @return bool True if and only if the password matches with the hash value.
   */
  public static function passwordVerify($thePassword, $theHash)
  {
    return password_verify($thePassword, $theHash);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
