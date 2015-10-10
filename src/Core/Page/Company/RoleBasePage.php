<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting and updating details of a role for the target company.
 */
abstract class RoleBasePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  /**
   * The ID of the role that is been inserted or updated.
   *
   * @var int
   */
  protected $myRolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a role of the target company.
   *
   * @return null
   */
  abstract protected function databaseAction();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $this->loadValues();

    if ($this->myForm->isSubmitted('submit'))
    {
      $this->myForm->loadSubmittedValues();

      $valid = $this->myForm->validate();
      if (!$valid)
      {
        $this->echoForm();
      }
      else
      {
        $this->databaseAction();

        Http::redirect(RoleDetailsPage::getUrl($this->myActCmpId, $this->myRolId));
      }
    }
    else
    {
      $this->echoForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form shown on this page.
   *
   * @return null
   */
  abstract protected function loadValues();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->myForm = new CoreForm();

    // Create form control for Company name.
    $input = $this->myForm->createFormControl('text', 'rol_name', 'Role');
    $input->setAttribute('maxlength', C::LEN_ROL_NAME);

    // Create form control for comment.
    $input = $this->myForm->createFormControl('text', 'rol_weight', 'Weight');
    $input->setAttribute('maxlength', C::LEN_ROL_WEIGHT);
    // XXX numeric

    $this->myForm->addButtons(Babel::getWord(C::WRD_ID_BUTTON_OK));
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
}

//----------------------------------------------------------------------------------------------------------------------

