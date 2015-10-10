<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\TabInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for inserting a page group.
 */
class TabInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->myUrl = TabInsertPage::getUrl();

    $this->myTitle = 'Create page tab';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
