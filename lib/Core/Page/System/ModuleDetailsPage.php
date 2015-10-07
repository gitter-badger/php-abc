<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\DetailTable;
use SetBased\Abc\Core\Table\OverviewTable;
use SetBased\Abc\Core\TableAction\System\ModuleUpdateTableAction;
use SetBased\Abc\Core\TableColumn\System\FunctionalityDetailsIconTableColumn;
use SetBased\Html\TableColumn\NumericTableColumn;
use SetBased\Html\TableColumn\TextTableColumn;
use SetBased\Html\TableRow\NumericTableRow;
use SetBased\Html\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with the details of a module.
 */
class ModuleDetailsPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the functionality.
   *
   * @var array
   */
  private $myDetails;

  /**
   * The ID of the functionality.
   *
   * @var int
   */
  private $myMdlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myMdlId = self::getCgiVar('mdl', 'mdl');

    $this->myDetails = Abc::$DL->systemModuleGetDetails($this->myMdlId, $this->myLanId);

    $this->appendPageTitle($this->myDetails['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $theMdlId The ID of the module.
   *
   * @return string
   */
  public static function getUrl($theMdlId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_SYSTEM_MODULE_DETAILS, 'pag');
    $url .= '/mdl/'.Abc::obfuscate($theMdlId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showDetails();

    $this->showFunctionalities();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details the module.
   */
  private function showDetails()
  {
    $table = new DetailTable();

    // Add table action for updating the module details.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->myMdlId));

    // Add row for the ID of the module.
    NumericTableRow::addRow($table, 'ID', $this->myDetails['mdl_id'], '%d');

    // Add row for the name of the module.
    TextTableRow::addRow($table, 'Module', $this->myDetails['mdl_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview table with all functionalities provides by the module.
   */
  private function showFunctionalities()
  {
    $functions = Abc::$DL->systemModuleGetFunctions($this->myMdlId, $this->myLanId);

    $table = new OverviewTable($this->myCmpId, $this->myUsrId);

    // Show function ID.
    $table->addColumn(new NumericTableColumn('ID', 'fun_id'));

    // Show function name.
    $table->addColumn(new TextTableColumn('Function', 'fun_name'));

    // Add column with link to view the details of the functionality.
    $table->addColumn(new FunctionalityDetailsIconTableColumn());

    echo $table->getHtmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
