<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\ModuleUpdateTableAction;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an the details of a compnay.
 */
class ModuleOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $theCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($theCmpId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_MODULE_OVERVIEW, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $modules = Abc::$DL->companyModuleGetAllEnabled($this->myActCmpId, $this->myLanId);

    $table = new CoreOverviewTable($this->myCmpId, $this->myUsrId);

    // Add table action for modifying the enabled modules of the Company.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->myActCmpId));

    // Show the ID of the module.
    $table->addColumn(new NumericTableColumn('ID', 'mdl_id'));

    // Show the name of the module.
    $col = $table->addColumn(new TextTableColumn('Model', 'mdl_name'));
    $col->sortOrder(1);

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
