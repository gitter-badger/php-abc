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
class Clean
{
  //--------------------------------------------------------------------------------------------------------------------
  public static function pruneWhitespace( $theValue )
  {
    if (empty($theValue))
    {
      return $theValue;
    }
    else
    {
      return trim( mb_ereg_replace( '[\ \t\n\r\0\x0B\xA0]+', ' ', $theValue, 'p' ) );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function normalizeUrl( $theValue )
  {
    $value = self::trimWhitespace( $theValue );

    if ($value==='' || $value===null || $value===false)
    {
      return '';
    }

    $parts = parse_url( $value );
    if (!is_array( $parts ))
    {
      return '';
    }

    if (sizeof( $parts )==1 && isset($parts['path']))
    {
      $i = strpos( $parts['path'], '/' );
      if ($i===false)
      {
        $parts['host'] = $parts['path'];
        unset($parts['path']);
      }
      else
      {
        $parts['host'] = substr( $parts['path'], 0, $i );
        $parts['path'] = substr( $parts['path'], $i );
      }
    }

    if (isset($parts['scheme']))
    {
      $sep = (strtolower( $parts['scheme'] )=='mailto' ? ':' : '://');
      $url = strtolower( $parts['scheme'] ).$sep;
    }
    else
    {
      $url = 'http://';
    }

    if (!$parts['path'] && strtolower( $parts['scheme'] )!='mailto')
    {
      $parts['path'] = '/';
    }

    if (isset($parts['pass']))
    {
      $url .= $parts['user'].':'.$parts['pass'].'@';
    }
    elseif (isset($parts['user']))
    {
      $url .= $parts['user'].'@';
    }

    if (isset($parts['host']))
    {
      $url .= $parts['host'];
    }
    if (isset($parts['port']))
    {
      $url .= ':'.$parts['port'];
    }
    if (isset($parts['path']))
    {
      $url .= $parts['path'];
    }
    if (isset($parts['query']))
    {
      $url .= '?'.$parts['query'];
    }
    if (isset($parts['fragment']))
    {
      $url .= '#'.$parts['fragment'];
    }

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function trimWhitespace( $theValue )
  {
    if (empty($theValue))
    {
      return $theValue;
    }
    else
    {
      return trim( $theValue, " \t\n\r\0\x0B\xA0" );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function tidyHtml( $theValue )
  {
    $value = self::trimWhitespace( $theValue );

    if ($value==='' || $value===null || $value===false)
    {
      return false;
    }

    $tidy_config = array('clean'          => false,
                         'output-xhtml'   => true,
                         'show-body-only' => true,
                         'wrap'           => 100);

    $tidy = new \tidy;

    $tidy->parseString( $value, $tidy_config, 'utf8' );
    $tidy->cleanRepair();
    $value = trim( tidy_get_output( $tidy ) );

    if (preg_match( '/^(([\ \r\n\t])|(<p>)|(<\/p>)|(&nbsp;))*$/', $value )==1)
    {
      $value = '';
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
