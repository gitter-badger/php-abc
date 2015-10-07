<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\OverviewTable;
use SetBased\Abc\Core\TableAction\System\ModuleInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\ModuleDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\ModuleUpdateIconTableColumn;
use SetBased\Html\TableColumn\NumericTableColumn;
use SetBased\Html\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with overview of all modules.
 */
class ModuleOverviewPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return '/pag/'.Abc::obfuscate(C::PAG_ID_SYSTEM_MODULE_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $modules = Abc::$DL->systemModuleGetAll($this->myLanId);

    $table = new OverviewTable();

    // Add table action for inserting a new module.
    $table->addTableAction('default', new ModuleInsertTableAction());

    // Show the ID of the module.
    $table->addColumn(new NumericTableColumn('ID', 'mdl_id'));

    // Show the name of the module.
    $col = $table->addColumn(new TextTableColumn('Module', 'mdl_name'));
    $col->sortOrder(1);

    // Add column with icon to view the details of the module.
    $table->addColumn(new ModuleDetailsIconTableColumn());

    // Add column with icon to modify the details of the module.
    $table->addColumn(new ModuleUpdateIconTableColumn());

    echo $table->getHtmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
