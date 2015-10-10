<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a word.
 */
class WordUpdatePage extends WordBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myWrdId = self::getCgiVar('wrd', 'wrd');

    $this->myDetails = Abc::$DL->wordGetDetails($this->myWrdId, $this->myActLanId);

    $this->myWdgId = $this->myDetails['wdg_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int  $theWrdId  The ID of the word.
   * @param bool $theRetUrl The URL to redirect the user agent.
   *
   * @return string
   */
  public static function getUrl($theWrdId, $theRetUrl = null)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_BABEL_WORD_UPDATE, 'pag');
    $url .= '/wrd/'.Abc::obfuscate($theWrdId, 'wrd');
    if ($theRetUrl) $url .= ";returl=".urlencode($theRetUrl);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the word.
   */
  protected function databaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    // Return immediately when no form controls are changed.
    if (!$changes) return;

    Abc::$DL->wordUpdateDetails($this->myWrdId,
                                $values['wdg_id'],
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
    $this->myForm->setValues($this->myDetails);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

