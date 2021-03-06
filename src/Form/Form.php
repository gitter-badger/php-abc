<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form;

use SetBased\Abc\Abc;
use SetBased\Abc\Error\RuntimeException;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for forms with protection against CSRF.
 *
 * This form class protects against CSRF attacks by means of State Full Double Submit Cookie.
 */
class Form extends RawForm
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The handler for echoing this this form.
   *
   * @var string
   */
  protected $myEchoHandler;

  /**
   * If set the generated form has protection against CSRF.
   *
   * @var bool
   */
  protected $myEnableCsrfCheck;

  /**
   * FieldSet for all form control elements of type "hidden".
   *
   * @var FieldSet
   */
  protected $myHiddenFieldSet;

  /**
   * The handlers for handling submits of this form.
   *
   * @var array
   */
  protected $mySubmitHandlers = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $theName
   * @param bool   $theCsrfCheckFlag If set the generated form has protection against CSRF.
   */
  public function __construct($theName = '', $theCsrfCheckFlag = true)
  {
    parent::__construct($theName);

    $this->myEnableCsrfCheck = $theCsrfCheckFlag;

    // Create a fieldset for hidden form controls.
    $this->myHiddenFieldSet = new FieldSet('');
    $this->addFieldSet($this->myHiddenFieldSet);

    // Set attribute for name (used by JavaScript).
    if ($theName!=='') $this->setAttrData('name', $theName);

    // Add hidden field for protection against CSRF.
    if ($this->myEnableCsrfCheck) $this->myHiddenFieldSet->createFormControl('silent', 'ses_csrf_token');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test whether a form control is submitted.
   *
   * @param string $theSubmitName The submit name of the form control.
   *
   * @return mixed
   */
  private static function testSubmitted($theSubmitName)
  {
    $parts = explode('[', str_replace(']', '', $theSubmitName));

    $ret = $_POST;
    foreach ($parts as $part)
    {
      if (!isset($ret[$part]))
      {
        $ret = null;
        break;
      }
      else
      {
        $ret = $ret[$part];
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a hidden form control to the fieldset for hidden form controls.
   *
   * @param Control $theControl The hidden form control.
   */
  public function addHiddenFormControl($theControl)
  {
    $this->myHiddenFieldSet->addFormControl($theControl);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Appends an event handler for form submit.
   *
   * @param Control $theControl The form control that submits the form.
   * @param string  $theMethod  The method for handling the form submit.
   */
  public function addSubmitHandler($theControl, $theMethod)
  {
    $this->mySubmitHandlers[] = ['control' => $theControl,
                                 'method'  => $theMethod];
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Defends against CSRF attacks using State Full Double Submit Cookie.
   *
   * @throws RuntimeException
   */
  public function csrfCheck()
  {
    // Return immediately if CSRF check is disabled.
    if (!$this->myEnableCsrfCheck) return;

    $control = $this->myHiddenFieldSet->getFormControlByName('ses_csrf_token');

    // If CSRF tokens (from session and from submitted form) don't match: possible CSRF attack.
    $ses_csrf_token1 = Abc::getInstance()->getCsrfToken();
    $ses_csrf_token2 = $control->getSubmittedValue();
    if ($ses_csrf_token1!==$ses_csrf_token2)
    {
      throw new RuntimeException('Possible CSRF attack.');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes this form. Executes means:
   * <ul>
   * <li> If the form is submitted the submitted values are validated:
   *      <ul>
   *      <li> If the submitted values are valid the appropriated handler is returned.
   *      <li> Otherwise the form is shown.
   *      </ul>
   * <li> Otherwise the form is shown.
   * </ul>
   *
   * @return string|null The appropriate handler method.
   */
  public function execute()
  {
    // Prepare the form.
    $this->prepare();

    // Is this form submitted?
    // @todo implement event types
    // @todo implement submit without button (i.e. submit via JS)
    $handler   = null;
    $submitted = null;
    foreach ($this->mySubmitHandlers as $handler)
    {
      /** @var Control $control */
      $control = $handler['control'];
      if (self::testSubmitted($control->getSubmitName()))
      {
        $submitted = true;
        break;
      }
    }

    // @todo implement dependant controls.

    $method = null;
    if ($submitted)
    {
      $this->loadSubmittedValues();
      $valid = $this->validate();
      if (!$valid)
      {
        if (isset($this->myEchoHandler)) $method = $this->myEchoHandler;
        else                             echo $this->generate();
      }
      else
      {
        $method = $handler['method'];
      }
    }
    else
    {
      if (isset($this->myEchoHandler)) $method = $this->myEchoHandler;
      else                             echo $this->generate();
    }

    return $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the hidden fieldset of this form.
   *
   * @return FieldSet
   */
  public function getHiddenFieldSet()
  {
    return $this->myHiddenFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values and protects against CSRF attacks using State Full Double Submit Cookie.
   *
   * The white listed values can be obtained with method {@link getValues).
   */
  public function loadSubmittedValues()
  {
    parent::loadSubmittedValues();

    $this->csrfCheck();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the handler for echo the HTML code of this form. If not set form will generated and echoed.
   *
   * @param string $theMethod The method for echoing the form.
   */
  public function setEchoHandler($theMethod)
  {
    $this->myEchoHandler = $theMethod;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
