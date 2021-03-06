<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a word.
 */
class WordInsertPage extends WordBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWdgId       = self::getCgiVar('wdg', 'wdg');
    $this->myButtonWrdId = C::WRD_ID_BUTTON_INSERT;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theWdgId The ID of the word group.
   *
   * @return string
   */
  public static function getUrl($theWdgId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_INSERT, 'pag');
    $url .= self::putCgiVar('wdg', $theWdgId, 'wdg');
    $url .= self::putCgiVar('act_lan', C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a word.
   */
  protected function databaseAction()
  {
    $values = $this->myForm->getValues();

    $this->myWrdId = Abc::$DL->WordInsertWord($this->myUsrId,
                                              $this->myWdgId,
                                              $values['wrd_label'],
                                              $values['wrd_comment'],
                                              $values['wdt_text']);
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

