<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\DetailTable;
use SetBased\Abc\Core\Table\OverviewTable;
use SetBased\Abc\Core\TableAction\Company\RoleUpdateFunctionalitiesTableAction;
use SetBased\Abc\Core\TableColumn\System\FunctionalityDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageDetailsIconTableColumn;
use SetBased\Html\TableColumn\NumericTableColumn;
use SetBased\Html\TableColumn\TextTableColumn;
use SetBased\Html\TableRow\NumericTableRow;
use SetBased\Html\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with information about a role.
 */
class RoleDetailsPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var int The ID of the role of which data is shown on this page.
   */
  protected $myRolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myRolId = self::getCgiVar('rol', 'rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theCmpId The ID of the target company.
   * @param int $theRolId The ID of the role.
   *
   * @return string
   */
  public static function getUrl($theCmpId, $theRolId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_ROLE_DETAILS, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');
    $url .= '/rol/'.Abc::obfuscate($theRolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showRole();

    $this->showFunctionalities();

    $this->showPages();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the functionalities that are granted to the role shown on this page.
   */
  private function showFunctionalities()
  {
    $functionalities = Abc::$DL->companyRoleGetFunctionalities($this->myActCmpId, $this->myRolId, $this->myLanId);

    $table = new OverviewTable($this->myCmpId, $this->myUsrId);

    // Add table action for modifying the granted functionalities.
    $table->addTableAction('default', new RoleUpdateFunctionalitiesTableAction($this->myActCmpId, $this->myRolId));


    // Show the ID of the module.
    $table->addColumn(new NumericTableColumn('ID', 'mdl_id'));

    // Show name of module.
    $col = $table->addColumn(new TextTableColumn('Module', 'mdl_name'));
    $col->sortOrder(1);

    // Show the ID of the functionality.
    $table->addColumn(new NumericTableColumn('ID', 'fun_id'));

    // Show name of functionality.
    $col = $table->addColumn(new TextTableColumn('Functionality', 'fun_name'));
    $col->sortOrder(2);

    // Add column with icon a link to view the details of the functionality.
    $table->addColumn(new FunctionalityDetailsIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the pages that the functionality shown on this page grants access to.
   */
  private function showPages()
  {
    $pages = Abc::$DL->companyRoleGetPages($this->myActCmpId, $this->myRolId, $this->myLanId);

    $table = new OverviewTable($this->myCmpId, $this->myUsrId);

    // Show page ID.
    $table->addColumn(new NumericTableColumn('ID', 'pag_id'));

    // Show class name.
    $col = $table->addColumn(new TextTableColumn('Class', 'pag_class'));
    $col->sortOrder(1);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    // Show modifying the page.
    $table->addColumn(new PageDetailsIconTableColumn($this->myLanId));

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the role.
   */
  private function showRole()
  {
    $details = Abc::$DL->companyRoleGetDetails($this->myActCmpId, $this->myRolId);

    $table = new DetailTable();

    // Add table action for update the Company details.
    // $table->addTableAction('default',new CompanyUpdateTableAction( $this->myRlsId));

    // Add row for role ID.
    NumericTableRow::addRow($table, 'ID', $details['rol_id'], '%d');

    // Add row for role name.
    TextTableRow::addRow($table, 'Role', $details['rol_name']);

    /// Add row for weight.
    NumericTableRow::addRow($table, 'Weight', $details['rol_weight'], '%d');

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

