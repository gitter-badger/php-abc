<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a word group.
 */
class WordGroupInsertPage extends WordGroupBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_BABEL_WORD_GROUP_INSERT, 'pag');
    $url .= "/?act_lan_id=".C::LAN_ID_BABEL_REFERENCE;

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Actually inserts a word group.
   */
  protected function databaseAction()
  {
    $values = $this->myForm->getValues();

    $this->myWdgId = Abc::$DL->wordGroupInsertDetails($values['wdg_name'], $values['wdg_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function setValues()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

