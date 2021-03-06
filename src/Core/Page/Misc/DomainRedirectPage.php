<?php
namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\HiddenControl;
use SetBased\Abc\Form\Form;
use SetBased\Abc\Helper\Http;
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for redirecting the user agent from the general domain (e.g. www.example.com) to the company specific domain
 * (e.g. setbased.example.com).
 */
class DomainRedirectPage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form used (but not shown) by this page.
   *
   * @var Form
   */
  private $myForm;

  /**
   * The requested URL (to which the user agent must be redirected).
   *
   * @var string
   */
  private $myRedirect;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct($theAbc)
  {
    parent::__construct();

    $this->myRedirect = self::getCgiUrl('redirect');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL to this page.
   *
   * @param string $theRequest The URL to which the user agent is redirect on success.
   *
   * @return string The URL to this page.
   */
  public static function getUrl($theRequest)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_USER_DOMAIN_REDIRECT, 'pag');
    $url .= self::putCgiVar('redirect', $theRequest);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Securely sets cookies for the company specific domain.
   */
  public function echoPage()
  {
    $this->createForm();
    if ($_SERVER['REQUEST_METHOD']=='POST')
    {
      $this->myForm->loadSubmittedValues();
      if ($this->myForm->validate())
      {
        $this->handleForm();
      }
      else
      {
        // Fall back to general URL of OnzeRelaties.
        $parts = explode('.', $_SERVER['SERVER_NAME']);
        Http::redirect('https://www.'.$parts[1].'.'.$parts[2]);
      }
    }
    else
    {
      // Fall back to general URL of OnzeRelaties.
      $parts = explode('.', $_SERVER['SERVER_NAME']);
      Http::redirect('https://www.'.$parts[1].'.'.$parts[2]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->myForm = new Form();
    $fieldset     = $this->myForm->addFieldSet(new FieldSet(''));

    // Add hidden control for cdr_token2.
    $hidden = new HiddenControl('cdr_token2');
    $fieldset->addFormControl($hidden);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function handleForm()
  {
    // Get cdr_token1 from cookie.
    $cdr_token1 = ($_COOKIE['SLD_SESSION']) ? $_COOKIE['SLD_SESSION'] : null;

    // Get cdr_token2 from form.
    $values     = $this->myForm->getValues();
    $cdr_token2 = $values['cdr_token2'];

    // Get session by cdr_token's.
    $session = Abc::$DL->sessionGetSessionByRedirectTokens($cdr_token1, $cdr_token2);

    $parts = explode('.', $_SERVER['SERVER_NAME']);

    // Unset SLD token.
    setcookie('SLD_SESSION', false, false, '/', $parts[1].'.'.$parts[2], true);

    if ($session && mb_strtolower($session['cmp_abbr'])==$parts[0])
    {
      // Set session cookie.
      setcookie('ses_session_token', $session['ses_session_token'], false, '/', $_SERVER['SERVER_NAME'], true, true);

      // Set CSRF cookie.
      setcookie('ses_csrf_token', $session['ses_csrf_token'], false, '/', $_SERVER['SERVER_NAME'], true, false);

      // Redirect the browser to the requested page (if any).
      Http::redirect(($this->myRedirect) ? $this->myRedirect : '/');
    }
    else
    {
      // Just in case fall back to general URL.
      Http::redirect('https://www.'.$parts[1].'.'.$parts[2]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
