<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 * @par Copyright:
 * Set Based IT Consultancy
 * $Date: 2011/09/14 19:55:10 $
 * $Revision: 1.2 $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Static class with helper functions for generating HTML code.
 */
class Html
{
  /** Counter for generating unique element ID's. See method @a getAutoId.
   */
  private static $ourAutoId = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Throws an exception with text @a $theMessage.
   *
   * @throws \Exception
   */
  public static function error()
  {
    $args   = func_get_args();
    $format = array_shift( $args );

    foreach ($args as &$arg)
    {
      if (!is_scalar( $arg ))
      {
        $arg = var_export( $arg, true );
      }
    }

    throw new \Exception(vsprintf( $format, $args ));
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function txt2Html( $theText )
  {
    return htmlspecialchars( $theText, ENT_QUOTES, 'UTF-8' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns a string with attribute @a $theName with value @a $theValue, e.g. type='text'. This function takes care
   *  about proper escaping of @a $theValue.
   */
  public static function generateAttribute( $theName, $theValue )
  {
    $ret = '';

    switch ($theName)
    {
      // Boolean attributes
      case 'checked':
      case 'disabled':
      case 'ismap':
      case 'multiple':
      case 'readonly':
      if (!empty($theValue))
      {
        $ret = " $theName='$theName'";
      }
        break;

      default:
        if ($theValue!==null && $theValue!==false && $theValue!=='' && substr( $theName, 0, 4 )!='set_')
        {
          $ret = ' ';
          $ret .= htmlspecialchars( $theName, ENT_QUOTES, 'UTF-8' );
          $ret .= "='";
          $ret .= htmlspecialchars( $theValue, ENT_QUOTES, 'UTF-8' );
          $ret .= "'";
        }
        break;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns an string that can be safely used an ID for an element. The format of the id is 'set_<n>' where n is
   * incremented with call to @a GetAutoId.
   */
  public static function getAutoId()
  {
    self::$ourAutoId++;

    return 'set_'.self::$ourAutoId;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
