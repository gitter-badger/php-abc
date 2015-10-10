<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\CompanyInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for inserting a company.
 */
class CompanyInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->myUrl = CompanyInsertPage::getUrl();

    $this->myTitle = 'Create Company';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
