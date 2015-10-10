<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\RoleInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for inserting a role.
 */
class RoleInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theActCmpId The ID od the target company.
   */
  public function __construct($theActCmpId)
  {
    $this->myUrl = RoleInsertPage::getUrl($theActCmpId);

    $this->myTitle = 'Create role';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
