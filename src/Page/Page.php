<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Page;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\Page\Misc\W3cValidatePage;
use SetBased\Abc\Error\InvalidUrlException;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\Url;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for all pages.
 */
abstract class Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The Company id (cmp_id) of the page requester.
   *
   * @var int
   */
  protected $myCmpId;

  /**
   * CSS code to be included in the head of this page.
   */
  protected $myCss = null;

  /**
   * List with CSS sources to be included on this page.
   *
   * @var string[]
   */
  protected $myCssSources = [];

  /**
   * JavaScript code to be included in the head of this page.
   *
   * @var string
   */
  protected $myJavaScript = null;

  /**
   * The keywords to be included in a meta tag for this page.
   *
   * var string[]
   */
  protected $myKeywords = [];

  /**
   * The preferred language (lan_id) of the page requester.
   *
   * @var int
   */
  protected $myLanId;

  /**
   * The user ID (usr_id) of the page requestor.
   *
   * @var int
   */
  protected $myUsrId;

  /**
   * The path where the HTML code of this page is stored for the W3C validator.
   *
   * @var string
   */
  protected $myW3cPathName;

  /**
   * If set to true (typically on DEV environment) the HTML code of this page will be validated by the W3C validator.
   *
   * @var bool
   */
  protected $myW3cValidate = false;

  /**
   * The size (in bytes) of the HTML code of this page.
   *
   * @var int
   */
  private $myPageSize = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $abc = Abc::getInstance();

    $this->myCmpId = $abc->getCmpId();
    $this->myUsrId = $abc->getUsrId();
    $this->myLanId = $abc->getLanId();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the value of a CGI variable holding an URL.
   *
   * This method will protect against unvalidated redirects, see
   * <https://www.owasp.org/index.php/Unvalidated_Redirects_and_Forwards_Cheat_Sheet>.
   *
   * @param string $theVarName           The name of the CGI variable.
   * @param bool   $theForceRelativeFlag If set the URL must be a relative URL. If the URL is not a relative URL an
   *                                     exception will be thrown.
   *
   * @return string
   * @throws InvalidUrlException
   */
  public static function getCgiUrl($theVarName, $theForceRelativeFlag = true)
  {
    if (isset($_GET[$theVarName]))
    {
      $url = urldecode($_GET[$theVarName]);
      if ($theForceRelativeFlag && !Url::isRelative($url))
      {
        throw new InvalidUrlException("Value '%s' of CGI variable '%s' is not a relative URL.", $url, $theVarName);
      }

      return $url;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a CGI variable. If the CGI variable is an obfuscated database ID the value will be
   * de-obfuscated.
   *
   * @param string $theVarName The name of the CGI variable.
   * @param string $theLabel   Must only be used if the CGI variable is an obfuscate database ID. An alias for the
   *                           column holding database ID and must corresponds with label that was used to obfuscate the
   *                           database ID.
   *
   * @return string|int
   */
  public static function getCgiVar($theVarName, $theLabel = null)
  {
    if (isset($_GET[$theVarName]))
    {
      if (isset($theLabel)) return Abc::deObfuscate($_GET[$theVarName], $theLabel);

      return $_GET[$theVarName];
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a CCS file to the header of this page.
   *
   * @param string      $theSource The filename relative to the resource root of the CSS source.
   * @param string|null $theDevice The device for which the CSS source is optimized for. Possible values:
   *                               * null       Suitable for all devices. null is preferred over 'all'.
   *                               * aural      Speech synthesizers
   *                               * braille    Braille feedback devices
   *                               * handheld   Handheld devices (small screen, limited bandwidth)
   *                               * projection Projectors
   *                               * print      Print preview mode/printed pages
   *                               * screen     Computer screens
   *                               * tty        Teletypes and similar media using a fixed-pitch character grid
   *                               * tv         Television type devices (low resolution, limited scroll ability)
   */
  public function appendCssSource($theSource, $theDevice = null)
  {
    $this->myCssSources[] = ['href'  => $theSource,
                             'media' => $theDevice,
                             'rel'   => 'stylesheet',
                             'type'  => 'text/css'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a page specific CCS file to the header of this page.
   *
   * @param string      $theClassName The PHP class name, i.e. __CLASS__. Backslashes will be translated to forward
   *                                  slashes to construct the filename relative to the resource root of the CSS
   *                                  source.
   * @param string|null $theDevice    The device for which the CSS source is optimized for.
   *                                  * null       Suitable for all devices. null is preferred over 'all'.
   *                                  * aural      Speech synthesizers
   *                                  * braille    Braille feedback devices
   *                                  * handheld   Handheld devices (small screen, limited bandwidth)
   *                                  * projection Projectors
   *                                  * print      Print preview mode/printed pages
   *                                  * screen     Computer screens
   *                                  * tty        Teletypes and similar media using a fixed-pitch character grid
   *                                  * tv         Television type devices (low resolution, limited scroll ability)
   */
  public function appendPageSpecificCssSource($theClassName, $theDevice = null)
  {
    // Construct the filename of the CSS file.
    $filename = '/css/'.str_replace('\\', '/', $theClassName);
    if (isset($theDevice)) $filename .= '.'.$theDevice;
    $filename .= '.css';

    // On development environments throw exception if the CSS file is missing.
    if (isset($_ENV['ABC_ENV']) && $_ENV['ABC_ENV']=='dev')
    {
      $path = HOME.'/www'.$filename;
      if (!file_exists($path))
      {
        throw new \LogicException(sprintf("CSS file '%s' does not exists.", $filename));
      }
    }

    // Append the CSS source the the list of CSS sources.
    $this->myCssSources[] = ['href'  => $filename,
                             'media' => $theDevice,
                             'rel'   => 'stylesheet',
                             'type'  => 'text/css'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Using RequiresJS calls a function in the same namespace as the PHP class (where backslashes will be translated to
   * forward slashes). Example:
   * ```
   * $this->callPageSpecificJsFunction(__CLASS__, 'init');
   * ```
   *
   * @param string $theClassName      The PHP cass name, i.e. __CLASS__. Backslashes will be translated to forward
   *                                  slashes to construct the namespace.
   * @param string $theJsFunctionName The function name inside the namespace.
   * @param array  $args              The optional arguments for the function.
   */
  public function callPageSpecificJsFunction($theClassName, $theJsFunctionName, $args = [])
  {
    $this->myJavaScript .= 'require(["';
    $this->myJavaScript .= str_replace('\\', '/', $theClassName);
    $this->myJavaScript .= '"],function(page){\'use strict\';page.';
    $this->myJavaScript .= $theJsFunctionName;
    $this->myJavaScript .= '(';
    $this->myJavaScript .= implode(',', array_map('json_encode', $args));
    $this->myJavaScript .= ');});';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented in child classes to echo the actual page content, i.e. the inner HTML of the body tag.
   *
   * @return null
   */
  abstract public function echoPage();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the size (in byes) of the HTML code of this page.
   *
   * @return int
   */
  public function getPageSize()
  {
    return $this->myPageSize;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If this page can be requested via multiple URL and one URL is preferred this method must be overridden to return
   * the preferred URI of this page.
   *
   * Typically this method will be used when the URL contains some ID and an additional title.
   * Example:
   * Initially a page with an article about a book is created with title "Harry Potter and the Sorcerer's Stone" and the
   * URI is /book/123456/Harry_Potter_and_the_Sorcerer's_Stone.html. After this article has been edited the URI is
   * /book/123456/Harry_Potter_and_the_Philosopher's_Stone.html. The later URI is the preferred URI now.
   *
   * If the preferred URI is set and different from the requested URI the user agent will be redirected to the
   * preferred URI with HTTP status code 301 (Moved Permanently).
   *
   * @return string|null The preferred URI of this page.
   */
  public function getPreferredUri()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a word to the key words to be included in a meta tag for this page.
   *
   * @param string $theKeyword The keyword.
   */
  protected function addKeyword($theKeyword)
  {
    $this->myKeywords[] = $theKeyword;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds words to the key words to be included in a meta tag for this page.
   *
   * @param string[] $theKeywords The keywords.
   */
  protected function addKeywords($theKeywords)
  {
    $this->myKeywords = array_merge($this->myKeywords, $theKeywords);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Appends with a separator a string to the page title
   *
   * @param string $thePageTitleAddendum The text to append to the page title.
   */
  protected function appendPageTitle($thePageTitleAddendum)
  {
    Abc::getInstance()->appendPageTitle($thePageTitleAddendum);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the links to external style sheets and internal style sheet.
   */
  protected function echoCascadingStyleSheets()
  {
    // Echo links to external style sheets.
    foreach ($this->myCssSources as $css_source)
    {
      echo Html::generateVoidElement('link', $css_source);
    }

    // Echo the internal style sheet.
    if ($this->myCss)
    {
      echo '<style type="text/css" media="all">', $this->myCss, '</style>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only and only if there are keywords for this page echos the keywords meta tag.
   */
  protected function echoMetaTagKeywords()
  {
    if ($this->myKeywords)
    {
      echo '<meta name="keywords"', Html::generateAttribute('content', implode(',', $this->myKeywords)), '/>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the meta tags within the HTML document.
   */
  protected function echoMetaTags()
  {
    // Echo meta tag that specifies the character encoding.
    echo '<meta', Html::generateAttribute('charset', Html::$ourEncoding), '/>';

    // Echo meta tag for keywords (if any).
    $this->echoMetaTagKeywords();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document leader, i.e. the opening html tag, the head element, and opening body tag.
   */
  protected function echoPageLeader()
  {
    $lan_code = Abc::getInstance()->getLanCode();
    echo '<!DOCTYPE html>';
    echo '<html xmlns="http://www.w3.org/1999/xhtml"', Html::generateAttribute('xml:lang', $lan_code),
    Html::generateAttribute('lang', $lan_code), '>';
    echo '<head>';

    // Echo the meta tags.
    $this->echoMetaTags();

    // Echo the title of the XHTML document.
    echo '<title>', Html::txt2html(Abc::getInstance()->getPageTitle()), '</title>';

    // Echo style sheets (if any).
    $this->echoCascadingStyleSheets();

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document trailer, i.e. the closing body and html tags, including the JavaScript code that will be
   * executed using RequireJS.
   */
  protected function echoPageTrailer()
  {
    echo '<script data-main="/js/abc" src="/js/require.js"></script>';
    if ($this->myJavaScript)
    {
      echo '<script type="text/javascript">/*<![CDATA[*/require([],function(){', $this->myJavaScript,
      '});/*]]>*/</script>';
    }

    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables validation of the HTML code of this page by the W3C Validator.
   */
  protected function enableW3cValidator()
  {
    $w3c_file            = uniqid('w3c_validator_').'.xhtml';
    $this->myW3cValidate = true;
    $this->myW3cPathName = DIR_TMP.'/'.$w3c_file;
    $url                 = W3cValidatePage::getUrl($w3c_file);
    $this->callPageSpecificJsFunction(__CLASS__, 'w3cValidate', [$url]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getPagIdOrg()
  {
    return Abc::getInstance()->getPagIdOrg();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the title of this page.
   *
   * @return string
   */
  protected function getPageTitle()
  {
    return Abc::getInstance()->getPageTitle();
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getPtbId()
  {
    return Abc::getInstance()->getPtbId();
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function setPageSize($theSize)
  {
    $this->myPageSize = $theSize;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title for current page.
   *
   * @param string $thePageTitle The new title of the page.
   */
  protected function setPageTitle($thePageTitle)
  {
    Abc::getInstance()->setPageTitle($thePageTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
