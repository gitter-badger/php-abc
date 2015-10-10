<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\RoleUpdateFunctionalitiesPage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for modifying the granted functionalities to a role.
 */
class RoleUpdateFunctionalitiesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theTargetCmpId The ID of the target company of the role.
   * @param int $theRolId       The ID of the role.
   */
  public function __construct($theTargetCmpId, $theRolId)
  {
    $this->myUrl = RoleUpdateFunctionalitiesPage::getUrl($theTargetCmpId, $theRolId);

    $this->myTitle = 'Modify functionalities';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
