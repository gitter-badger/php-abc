<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a word group.
 */
class WordGroupUpdatePage extends WordGroupBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWdgId       = self::getCgiVar('wdg', 'wdg');
    $this->myDetails     = Abc::$DL->WordGroupGetDetails($this->myWdgId);
    $this->myButtonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theWdgId
   *
   * @return string
   */
  public static function getUrl($theWdgId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_BABEL_WORD_GROUP_UPDATE, 'pag');
    $url .= '/wdg/'.Abc::obfuscate($theWdgId, 'wdg');
    $url .= '/act_lan/'.Abc::obfuscate(C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the word group.
   */
  protected function databaseAction()
  {
    $values = $this->myForm->getValues();

    Abc::$DL->wordGroupUpdateDetails($this->myWdgId, $values['wdg_name'], $values['wdg_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function setValues()
  {
    $this->myForm->setValues($this->myDetails);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

