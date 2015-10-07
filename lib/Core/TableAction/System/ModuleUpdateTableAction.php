<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\ModuleUpdatePage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for updating the details of a module.
 */
class ModuleUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theMdlId The ID of the module.
   */
  public function __construct($theMdlId)
  {
    $this->myUrl = ModuleUpdatePage::getUrl($theMdlId);

    $this->myTitle = 'Modify module';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
