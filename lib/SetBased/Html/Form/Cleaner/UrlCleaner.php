<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class PruneWhitespaceCleaner
 * @package SetBased\Html\Form\Cleaner
 */
class UrlCleaner implements Cleaner
{
  /**
   * @var UrlCleaner The singleton instance of this class.
   */
  static private $ourSingleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return PruneWhitespaceCleaner
   */
  public static function get()
  {
    if (!self::$ourSingleton) self::$ourSingleton = new self();

    return self::$ourSingleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns @a $theValue as a normalized URL.
   *
   * @param string $theValue
   *
   * @return string
   */
  public function clean( $theValue )
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean( $theValue );

    // If the value is empty return immediately,
    if ($value==='' || $value===null || $value===false)
    {
      return '';
    }

    // Split the URL in parts.
    $parts = parse_url( $value );
    if (!is_array( $parts ))
    {
      return $value;
    }

    // Recompose the parts of the URL is a normalized URL.
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

    if (isset($parts['host'])) $url .= $parts['host'];
    if (isset($parts['port'])) $url .= ':'.$parts['port'];
    if (isset($parts['path'])) $url .= $parts['path'];
    if (isset($parts['query'])) $url .= '?'.$parts['query'];
    if (isset($parts['port'])) $url .= ':'.$parts['port'];
    if (isset($parts['path'])) $url .= $parts['path'];
    if (isset($parts['query'])) $url .= '?'.$parts['query'];
    if (isset($parts['fragment'])) $url .= '#'.$parts['fragment'];

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
