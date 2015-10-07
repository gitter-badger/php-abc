<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting and updating a functionality.
 */
abstract class FunctionalityBasePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a functionality.
   *
   * @return null
   */
  abstract protected function dataBaseAction();

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
        $this->dataBaseAction();

        Http::redirect(FunctionalityOverviewPage::getUrl());
      }
    }
    else
    {
      $this->echoForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form.
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
    $modules = Abc::$DL->systemModuleGetAll($this->myLanId);
    $words   = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_FUNCTIONALITIES, $this->myLanId);

    $this->myForm = new CoreForm($this->myLanId);

    $control = $this->myForm->createFormControl('select', 'mdl_id', 'Module');
    $control->setOptions($modules, 'mdl_id', 'mdl_name');
    $control->setEmptyOption(' ');

    $control = $this->myForm->createFormControl('select', 'wrd_id', 'Name');
    $control->setOptions($words, 'wrd_id', 'wrd_text');
    $control->setEmptyOption(' ');

    $control = $this->myForm->createFormControl('text', 'fun_name', 'Name');
    $control->setAttribute('maxlength', C::LEN_WDT_TEXT);

    $this->myForm->addButtons(Babel::getWord(C::WRD_ID_BUTTON_OK));

    // $this->myForm->addFormValidator( new SystemFunctionalityInsertFormValidator() );
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
