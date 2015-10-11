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
 * Abstract parent page for inserting or modifying a page group.
 */
abstract class TabBasePage extends CorePage
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
   * Must implemented by child pages to actually insert or update a page group.
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
        Http::redirect(TabOverviewPage::getUrl());
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
    $this->myForm = new CoreForm();

    // Create select box for (known) page titles.
    $titles = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_PAGE_GROUP_TITLE, $this->myLanId);

    $input = $this->myForm->createFormControl('select', 'wrd_id', 'Title');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setEmptyOption(true);

    // Create text box for (new) page title.
    $input = $this->myForm->createFormControl('text', 'ptb_title', 'Title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);

    // Create form control for the page label.
    $input = $this->myForm->createFormControl('text', 'ptb_label', 'Label');
    $input->setAttrMaxLength(C::LEN_PTB_LABEL);

    // Add submit button.
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
