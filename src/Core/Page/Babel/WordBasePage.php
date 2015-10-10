<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\Http;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for pages for inserting and updating a word.
 */
abstract class WordBasePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm.
   */
  protected $myForm;

  /**
   * The ID of word group of the word (only used for creating a new word).
   *
   * @var int
   */
  protected $myWdgId;

  /**
   * The ID of the word.
   *
   * @var int
   */
  protected $myWrdId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a word.
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
    $this->echoWordGroupInfo();

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

        $values = $this->myForm->getValues();
        Http::redirect(WordGroupDetailsPage::getUrl($values['wdg_id'], $this->myActLanId));
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
    $ref_language = Abc::$DL->LanguageGetName($this->myRefLanId, $this->myRefLanId);
    // $act_language = \SetBased\Rank\Abc::$DL->LanguageGetName( $this->myActLanId, $this->myRefLanId );

    $this->myForm = new CoreForm($this->myLanId);

    // Create from control for word group name.
    $word_groups = Abc::$DL->wordGroupGetAll($this->myRefLanId);
    $input       = $this->myForm->createFormControl('select', 'wdg_id', 'Word Group', true);
    $input->setOptions($word_groups, 'wdg_id', 'wdg_name');
    $input->setValue($this->myWdgId);

    // Create form control for ID.
    if ($this->myWrdId)
    {
      $input = $this->myForm->createFormControl('span', 'wrd_id', 'ID');
      $input->setInnerText($this->myWrdId);
    }

    // Create form control for label.
    $input = $this->myForm->createFormControl('text', 'wrd_label', 'Label');
    $input->setAttribute('maxlength', C::LEN_WRD_LABEL);

    // Input for the actual word.
    $input = $this->myForm->createFormControl('text', 'wdt_text', Html::txt2html($ref_language), true);
    $input->setAttribute('maxlength', C::LEN_WDT_TEXT);

    // Create form control for comment.
    $input = $this->myForm->createFormControl('text', 'wrd_comment', 'Remark');
    $input->setAttribute('maxlength', C::LEN_WRD_COMMENT);

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
  /**
   * Echos brief info of the word group of the word.
   */
  private function echoWordGroupInfo()
  {
    $group = Abc::$DL->wordGroupGetDetails($this->myWdgId);

    $table = new CoreDetailTable($this->myCmpId, $this->myUsrId);

    // Add row for the ID of the word group.
    NumericTableRow::addRow($table, 'ID', $group['wdg_id'], '%d');

    // Add row for the name of the word group.
    TextTableRow::addRow($table, 'Word Group', $group['wdg_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

