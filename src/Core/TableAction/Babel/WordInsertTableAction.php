<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Babel;

use SetBased\Abc\Core\Page\Babel\WordInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for inserting a word.
 */
class WordInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theWdgId The ID of the word group of the new word.
   */
  public function __construct($theWdgId)
  {
    $this->myUrl = WordInsertPage::getUrl($theWdgId);

    $this->myTitle = 'Create word';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
