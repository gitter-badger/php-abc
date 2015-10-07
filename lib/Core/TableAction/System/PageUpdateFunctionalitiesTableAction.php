<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\PageUpdateFunctionalitiesPage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for modifying the functionalities that grant access to a target page.
 */
class PageUpdateFunctionalitiesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $thePagId The ID of the target page.
   */
  public function __construct($thePagId)
  {
    $this->myUrl = PageUpdateFunctionalitiesPage::getUrl($thePagId);

    $this->myTitle = 'Modify functionalities';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
