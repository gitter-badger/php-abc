<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\Form;
use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Helper\Http;
use SetBased\Abc\Helper\Password;
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for logging on the website.
 */
class LoginPage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The login form shown on this page.
   *
   * @var Form
   */
  protected $myForm;

  /**
   * If set the URI to which the user agent must redirected after a successful login.
   *
   * @var string
   */
  private $myRedirect;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myRedirect = self::getCgiUrl('redirect');

    if (isset($_SERVER['ABC_ENV']) && $_SERVER['ABC_ENV']=='dev')
    {
      $this->enableW3cValidator();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param string $theUri A URI to which the user agent must be redirected after a successful login.
   *
   * @return string
   */
  public static function getUrl($theUri = null)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_MISC_LOGIN, 'pag');
    if ($theUri) $url .= '/redirect/'.urlencode($theUri);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoPage()
  {
    // Buffer for actual contents.
    ob_start();

    $this->showPageContent();

    $contents = ob_get_contents();
    ob_end_clean();

    // Buffer for header.
    ob_start();

    $this->showPageHeader();

    // $this->echoMainMenu();
    // Show the actual content of the page.
    echo '<div id="container">';
    echo $contents;
    echo '</div>';

    $this->showPageTrailer();

    // Write the HTML code of this page to the file system for (asynchronous) validation.
    if ($this->myW3cValidate)
    {
      $contents = ob_get_contents();
      file_put_contents($this->myW3cPathName, $contents);
    }

    $this->setPageSize(ob_get_length());
    ob_end_flush();
  }

  //--------------------------------------------------------------------------------------------------------------------

  protected function showPageContent()
  {
    $this->createForm();

    if ($this->myForm->isSubmitted('submit'))
    {
      $login_succeeded = false;
      $this->myForm->loadSubmittedValues();

      if ($this->myForm->validate())
      {
        $login_succeeded = $this->handleForm();
      }

      if (!$login_succeeded) $this->echoForm();
    }
    else
    {
      $this->echoForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $abc = Abc::getInstance();

    $this->myForm = new CoreForm(false);

    $input = $this->myForm->createFormControl('text', 'usr_name', 'Naam', true);
    $input->setAttribute('maxlength', C::LEN_USR_NAME);

    $input = $this->myForm->createFormControl('password', 'usr_password', 'Wachtwoord', true);
    $input->setAttribute('size', C::LEN_USR_NAME);
    $input->setAttribute('maxlength', C::LEN_PASSWORD);

    if ($abc->getDomain())
    {
      /** @var SpanControl $input */
      $input = $this->myForm->createFormControl('span', 'dummy', 'Company');
      $input->setInnerText(strtolower($abc->getDomain()));

      /** @var ConstantControl $input */
      $input = $this->myForm->createFormControl('constant', 'cmp_abbr');
      $input->setValue($abc->getDomain());
    }
    else
    {
      $input = $this->myForm->createFormControl('text', 'cmp_abbr', 'Company', true);
      $input->setAttribute('maxlength', C::LEN_CMP_ABBR);
    }

    $this->myForm->addButtons('Login');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the form shown on this page.
   */
  private function echoForm()
  {
    echo $this->myForm->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if login is successful. Otherwise returns false.
   *
   * @return bool
   */
  private function handleForm()
  {
    $abc = Abc::getInstance();

    $values = $this->myForm->getValues();

    // Validate the user is allowed to login (except for password validation).
    $response1 = Abc::$DL->sessionLogin1($abc->getSesId(), $values['usr_name'], $values['cmp_abbr']);
    $lgr_id    = $response1['lgr_id'];

    if ($lgr_id==C::LGR_ID_GRANTED)
    {
      // So far, user is allowed to login. Last validation: verify password.
      $match = Password::passwordVerify($values['usr_password'], $response1['usr_password_hash']);
      if ($match!==true) $lgr_id = C::LGR_ID_WRONG_PASSWORD;
    }

    // Log the login attempt and set session.
    $response2 = Abc::$DL->sessionLogin2($abc->getSesId(),
                                         $response1['cmp_id'],
                                         $response1['usr_id'],
                                         $lgr_id,
                                         $values['usr_name'],
                                         $values['cmp_abbr'],
                                         $_SERVER['REMOTE_ADDR']);

    if ($response2['lgr_id']==C::LGR_ID_GRANTED)
    {
      // The user has logged on successfully.

      // First verify that the hash is sill up to date.
      if (Password::passwordNeedsRehash($response1['usr_password_hash']))
      {
        $hash = Password::passwordHash($values['usr_password']);
        Abc::$DL->userUpdatePasswordHash($this->myCmpId, $this->myUsrId, $hash);
      }

      $domain_redirect = false;
      if (!$abc->getDomain())
      {
        // Redirect browser to $values['cmp_abbr'].onzerelaties.net
        $parts = explode('.', $_SERVER['SERVER_NAME']);
        if (count($parts)==3 && $parts[0]=='www' && $_SERVER['HTTPS']=='on')
        {
          // Unset session and CSRF cookies.
          setcookie('ses_session_token', false, false, '/', $abc->getCanonicalServerName(), true, true);
          setcookie('ses_csrf_token', false, false, '/', $abc->getCanonicalServerName(), true, false);

          /*
          // Get tokens for cross domain redirect.
          $tokens = Abc::$DL->sessionGetRedirectTokens( $this->myRequestBus->getSesId() );

          // Set a (temporary) cookie that is valid at SLD.
          setcookie( 'cdr_token1', $tokens['cdr_token1'], false, '/', $parts[1].'.'.$parts[2], true, true );

          // Set token to be used in JavaScript.
          $values    = $this->myForm->getValues();
          $host_name = mb_strtolower( $values['cmp_abbr'] ).'.'.$parts[1].'.'.$parts[2];
          $url       = 'https://'.$host_name.DomainRedirectPage::getUrl( $this->myRedirect );

          $this->appendJavaScriptLine( 'PageMiscLogin.ourCdrToken2="'.$tokens['cdr_token2'].'";' ); // xxx escaping
          $this->appendJavaScriptLine( 'PageMiscLogin.ourCdrUrl="'.$url.'";' ); // xxx escaping

          $domain_redirect = true;
          */
        }
      }

      if (!$domain_redirect)
      {
        // Set the secure token.
        if ($_SERVER['HTTPS']=='on')
        {
          setcookie('ses_session_token',
                    $response2['ses_session_token'],
                    false,
                    '/',
                    $abc->getCanonicalServerName(),
                    true,
                    true);
          setcookie('ses_csrf_token',
                    $response2['ses_csrf_token'],
                    false,
                    '/',
                    $abc->getCanonicalServerName(),
                    true,
                    false);
        }

        Http::redirect(($this->myRedirect) ? $this->myRedirect : '/');
      }

      return true;
    }
    else
    {
      // The user has not logged on successfully.
      return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function showPageHeader()
  {
    echo '<!DOCTYPE html>';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl" dir="ltr">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>';

    // echo "<title>".SET_Html::Txt2Html( Babel::getWord( C::WRD_ID_USER_LOGIN, $this->myLanId ) )."</title>";
    foreach ($this->myCssSources as $css_source)
    {
      echo '<link rel="stylesheet" media="screen" type="text/css" href="', $css_source, '"/>';
    }
    if ($this->myCss)
    {
      echo '<style type="text/css" media="all">', $this->myCss, '</style>';
    }

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function showPageTrailer()
  {
    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
