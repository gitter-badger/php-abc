<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\SpecificPageInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action inserting a company specific page that overrides a standard page.
 */
class SpecificPageInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theActCmpId The ID of the target company.
   */
  public function __construct($theActCmpId)
  {
    $this->myUrl = SpecificPageInsertPage::getUrl($theActCmpId);

    $this->myTitle = 'Add new page';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
