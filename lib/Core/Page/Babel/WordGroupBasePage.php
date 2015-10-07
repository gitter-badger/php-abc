<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting and updating a word group.
 */
abstract class WordGroupBasePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  /**
   * The ID of the word group.
   *
   * @var int
   */
  protected $myWdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by a child page to actually insert or update a word group.
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
    $this->setValues();

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

        Http::redirect(WordGroupDetailsPage::getUrl($this->myWdgId, $this->myActLanId));
      }
    }
    else
    {
      $this->echoForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial values of the form shown on this page.
   *
   * @return null
   */
  abstract protected function setValues();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->myForm = new CoreForm($this->myLanId);

    // Show word group ID (update only).
    if ($this->myWdgId)
    {
      $input = $this->myForm->createFormControl('span', 'wdg_id', 'ID');
      $input->setInnerText($this->myWdgId);
    }

    // Input for the name of the word group.
    $input = $this->myForm->createFormControl('text', 'wdg_name', 'Name', true);
    $input->setAttribute('maxlength', C::LEN_WDG_NAME);

    // Input for the label of the word group.
    $input = $this->myForm->createFormControl('text', 'wdg_label', 'Label');
    $input->setAttribute('maxlength', C::LEN_WRD_LABEL);

    // Create a submit button.
    $this->myForm->addButtons('OK');
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

