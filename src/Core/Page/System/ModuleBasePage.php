<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\FormValidator\SystemModuleInsertFormValidator;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for inserting or updating the details of a module.
 */
abstract class ModuleBasePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  /**
   * @var int The ID of de module to be updated or inserted.
   */
  protected $myMdlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a module.
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

        Http::redirect(ModuleDetailsPage::getUrl($this->myMdlId));
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
    $words = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_MODULE, $this->myLanId);

    $this->myForm = new CoreForm($this->myLanId);

    if ($words)
    {
      // If there are unused modules names (i.e. words in the word group BBL_WDG_ID_MODULES that are not used by a
      // module) create a select box with free modules names.
      $input = $this->myForm->createFormControl('select', 'wrd_id', 'Module Name');
      $input->setOptions($words, 'wrd_id', 'wrd_text');
      $input->setEmptyOption(' ');
    }

    // Create a text box for (new) module name.
    $input = $this->myForm->createFormControl('text', 'mdl_name', 'Module Name');
    $input->setAttribute('maxlength', C::LEN_WDT_TEXT);


    // Add submit button.
    $this->myForm->addButtons(Babel::getWord(C::WRD_ID_BUTTON_OK));

    $this->myForm->addFormValidator(new SystemModuleInsertFormValidator());
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
