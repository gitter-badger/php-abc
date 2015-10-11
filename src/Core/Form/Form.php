<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Affirm\Affirm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for forms with CSRF protection.
 */
class Form extends \SetBased\Abc\Form\Form
{
  //--------------------------------------------------------------------------------------------------------------------
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

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param bool $theCsrfCheckFlag If set the generated form has protection against CSRF.
   */
  public function __construct($theCsrfCheckFlag = true)
  {
    parent::__construct();

    $this->myEnableCsrfCheck = $theCsrfCheckFlag;

    $this->myHiddenFieldSet = parent::createFieldSet('fieldset');

    // Turn auto complete off.
    $this->myAttributes['autocomplete'] = false;

    // Add hidden field for protection against CSRF.
    if ($this->myEnableCsrfCheck) $this->myHiddenFieldSet->createFormControl('hidden', 'ses_csrf_token');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theControl
   */
  public function addHiddenFormControl($theControl)
  {
    $this->myHiddenFieldSet->addFormControl($theControl);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Defends against CSRF attacks using State Full Double Submit Cookie.
   *
   * @throws \Exception
   */
  public function csrfCheck()
  {
    // Return immediately if CSRF check is disabled.
    if (!$this->myEnableCsrfCheck) return;

    // If CSRF tokens (from session and from submitted form) don't match: possible CSRF attack.
    $ses_csrf_token = Abc::getInstance()->getCsrfToken();
    if ($this->myValues['ses_csrf_token']!==$ses_csrf_token)
    {
      Affirm::assertFailed('Possible CSRF attack.');
    }

    // Remove ses_csrf_token from white listed values to prevent possible errors due to a bogus value.
    unset($this->myValues['ses_csrf_token']);

    // And remove ses_csrf_token from changed form controls also.
    unset($this->myChangedControls['ses_csrf_token']);
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
}

//----------------------------------------------------------------------------------------------------------------------
