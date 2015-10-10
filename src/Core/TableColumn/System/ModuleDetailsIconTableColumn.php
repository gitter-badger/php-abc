<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\ModuleDetailsPage;
use SetBased\Abc\Core\TableColumn\DetailsIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for updating the details of a functionality.
 */
class ModuleDetailsIconTableColumn extends DetailsIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($theRow)
  {
    return ModuleDetailsPage::getUrl($theRow['mdl_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
