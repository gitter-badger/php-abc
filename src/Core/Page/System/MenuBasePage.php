<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Form\Validator\IntegerValidator;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for inserting or updating a menu entry.
 */
abstract class MenuBasePage extends CorePage
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
   * Must implemented by child pages to actually insert or update a menu entry.
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

        Http::redirect(MenuOverviewPage::getUrl());
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
    $this->myForm = new CoreForm($this->myLanId);

    // Create select box for (known) page titles.
    $titles = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_MENU, $this->myLanId);

    $input = $this->myForm->createFormControl('select', 'wrd_id', 'Menu Title');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setEmptyOption('');


    // Create text box for the title the menu item.
    $input = $this->myForm->createFormControl('text', 'mnu_title', 'Menu Title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);


    // Create select box for chose page for menu.
    $pages   = Abc::$DL->systemPageGetAll($this->myLanId);
    $control = $this->myForm->createFormControl('select', 'pag_id', 'Page Class', true);
    $control->setOptions($pages, 'pag_id', 'pag_class');
    $control->setEmptyOption(' ');
    $control->setOptionsObfuscator(Abc::getObfuscator('pag'));


    // Create text form control for input menu level.
    $input = $this->myForm->createFormControl('text', 'mnu_level', 'Menu Level', true);
    $input->setAttrMaxLength(C::LEN_MNU_LEVEL);
    $input->setValue(1);
    $input->addValidator(new IntegerValidator(0, $this->myLanId));


    // Create text form control for input menu group.
    $input = $this->myForm->createFormControl('text', 'mnu_group', 'Menu Group', true);
    $input->setAttrMaxLength(C::LEN_MNU_GROUP);
    $input->addValidator(new IntegerValidator(0, $this->myLanId));


    // Create text form control for input menu weight.
    $input = $this->myForm->createFormControl('text', 'mnu_weight', 'Menu Weight', true);
    $input->setAttrMaxLength(C::LEN_MNU_WEIGHT);
    $input->addValidator(new IntegerValidator(0, $this->myLanId));


    // Create text box for URL of the menu item.
    $input = $this->myForm->createFormControl('text', 'mnu_link', 'Menu Link');
    $input->setAttrMaxLength(C::LEN_MNU_LINK);

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
