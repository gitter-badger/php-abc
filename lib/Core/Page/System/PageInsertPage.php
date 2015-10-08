<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a page.
 */
class PageInsertPage extends PageBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_SYSTEM_PAGE_INSERT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page.
   */
  protected function databaseAction()
  {
    $values = $this->myForm->getValues();
    if ($values['pag_title'])
    {
      $wrd_id = Abc::$DL->wordInsertWord($this->myUsrId,
                                         C::WDG_ID_PAGE_TITLE,
                                         false,
                                         false,
                                         $values['pag_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    $this->myTargetPagId = Abc::$DL->systemPageInsertDetails($wrd_id,
                                                             $values['ptb_id'],
                                                             $values['pag_id_org'],
                                                             $values['mnu_id'],
                                                             $values['pag_class'],
                                                             $values['pag_label'],
                                                             $values['pag_weight']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

