<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Form;
use SetBased\Abc\Helper\Http;

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

    $this->myActLanId = self::getCgiVar('act_lan', 'lan');
    if (!$this->myActLanId) $this->myActLanId = $this->myLanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the selected language for the languages the user is authorized.
   *
   * @return int The ID of the selected language.
   */
  public function selectLanguage()
  {
    //$languages = Abc::$DL->languageGetAllTranslator( $this->myUsrId, $this->myRefLanId );
    $languages = Abc::$DL->languageGetAllLanguages($this->myRefLanId);

    // If translator is authorized for 1 language return immediately.
    if (count($languages)==1)
    {
      $key = key($languages);

      $this->myActLanId = $languages[$key]['lan_id'];
    }

    $form   = $this->createSelectLanguageForm($languages);
    $method = $form->execute();
    switch ($method)
    {
      case null:
        // Nothing to do;
        break;

      case  'handleSelectLanguage':
        $this->handleSelectLanguage($form);
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param Form $theForm The form for selecting the language.
   */
  protected function handleSelectLanguage($theForm)
  {
    $values           = $theForm->getValues();
    $this->myActLanId = $values['babel']['act_lan_id'];

    $get            = $_GET;
    $get['act_lan'] = Abc::obfuscate($this->myActLanId, 'lan');

    $url = '';
    foreach ($get as $name => $value)
    {
      $url .= '/'.$name.'/'.$value;
    }

    Http::redirect($url);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function createSelectLanguageForm($languages)
  {
    $form = new CoreForm('babel', false);

    $input = $form->createFormControl('select', 'act_lan_id', C::WRD_ID_LANGUAGE, true);
    $input->setOptions($languages, 'lan_id', 'lan_name');
    $input->setOptionsObfuscator(Abc::getObfuscator('lan'));
    $input->setValue($this->myActLanId);

    // Create a submit button.
    $form->addSubmitButton(C::WRD_ID_BUTTON_OK, 'handleSelectLanguage');

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

