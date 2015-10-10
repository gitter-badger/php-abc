<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for all Babel pages.
 */
abstract class BabelPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The language ID to which the word/text/topic is been translated.
   *
   * @var int
   */
  protected $myActLanId;

  /**
   * The language ID from which the word/text/topic is been translated.
   *
   * @var int
   */
  protected $myRefLanId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct()
  {
    parent::__construct();

    $this->myRefLanId = C::LAN_ID_BABEL_REFERENCE;

    $this->myActLanId = self::getCgiVar('act_lan_id');
    if (!$this->myActLanId) $this->myActLanId = $this->myLanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the selected language for the languages the user is authorized.
   *
   * @param int $theTargetLanId
   *
   * @return int The ID of the selected language.
   */
  public function selectLanguage($theTargetLanId = 0)
  {
    //$languages = Abc::$DL->languageGetAllTranslator( $this->myUsrId, $this->myRefLanId );
    $languages = Abc::$DL->languageGetAllLanguages($this->myRefLanId);

    // If translator is authorized for 1 language return immediately.
    if (count($languages)==1)
    {
      $key = key($languages);

      return $languages[$key]['lan_id'];
    }

    $form = new CoreForm($this->myLanId);
    $form->setAttribute('method', 'get');

    $input = $form->createFormControl('select', 'act_lan_id', C::WRD_ID_LANGUAGE, true);
    $input->setOptions($languages, 'lan_id', 'lan_name');
    $input->setOptionsObfuscator(Abc::getObfuscator('lan'));
    $input->setValue($theTargetLanId);

    // Create a submit button.
    $form->addButtons(Babel::getWord(C::WRD_ID_BUTTON_OK));

    if ($form->isSubmitted('submit'))
    {
      $form->loadSubmittedValues();
      $valid = $form->validate();
      if ($valid)
      {
        $values           = $form->getValues();
        $this->myActLanId = $values['act_lan_id'];
      }
    }

    echo $form->generate();

    return $this->myActLanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

