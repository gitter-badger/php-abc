<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\BabelWordTranslateSlatControlFactory;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for translating all words in a word group.
 */
class WordTranslateWordsPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the words.
   *
   * @var array
   */
  protected $myDetails;

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
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWdgId = self::getCgiVar('wdg', 'wdg');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theWdgId The ID of the word group.
   * @param int $theLanId The target language.
   *
   * @return string
   */
  public static function getUrl($theWdgId, $theLanId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_BABEL_WORD_TRANSLATE_WORDS, 'pag');
    $url .= '/wdg/'.Abc::obfuscate($theWdgId, 'wdg');
    $url .= "/?act_lan_id=$theLanId";

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoTabContent()
  {
    $this->createForm();

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
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $words = Abc::$DL->wordGroupGetAllWordsTranslator($this->myWdgId, $this->myActLanId);

    $this->myForm = new CoreForm($this->myLanId);
    $this->myForm->setAttribute('class', 'input_table');

    // Add field set.
    $field_set = $this->myForm->createFieldSet();

    // Create factory.
    $factory = new BabelWordTranslateSlatControlFactory($this->myRefLanId, $this->myActLanId);
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = $button->createFormControl('submit', 'submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_OK));

    // Put everything together in a LoverControl.
    $louver = new LouverControl('data');
    $louver->setAttribute('class', 'overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($words);
    $louver->populate();

    // Add the LouverControl the the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translations of the words in the target language.
   */
  private function databaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    foreach ($changes['data'] as $wrd_id => $changed)
    {
      Abc::$DL->wordTranslateWord($this->myUsrId, $wrd_id, $this->myActLanId, $values['data'][$wrd_id]['act_wdt_text']);
    }
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

