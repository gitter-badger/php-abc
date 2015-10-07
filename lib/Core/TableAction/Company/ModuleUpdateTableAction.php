<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\ModuleUpdatePage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for setting the enabled modules of a company.
 */
class ModuleUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theCmpId The ID of the target company.
   */
  public function __construct($theCmpId)
  {
    $this->myUrl = ModuleUpdatePage::getUrl($theCmpId);

    $this->myTitle = 'Modify enabled modules';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
