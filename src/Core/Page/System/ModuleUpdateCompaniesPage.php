<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\SystemModuleUpdateCompaniesSlatControlFactory;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Helper\Http;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for granting or revoking a module to or from companies.
 */
class ModuleUpdateCompaniesPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the module.
   *
   * @var array
   */
  private $myDetails;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $myForm;

  /**
   * The ID of the module that will be granted or revoked to or from companies.
   *
   * @var int
   */
  private $myModId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myModId = self::getCgiVar('mdl', 'mdl');

    $this->myDetails = Abc::$DL->systemModuleGetDetails($this->myModId, $this->myLanId);

    $this->appendPageTitle($this->myDetails['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $theModId The ID of the module.
   *
   * @return string
   */
  public static function getUrl($theModId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_MODULE_UPDATE_COMPANIES, 'pag');
    $url .= self::putCgiVar('mdl', $theModId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showModule();

    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    // Get all available pages.
    $pages = Abc::$DL->systemModuleGetAvailableCompanies($this->myModId);

    // Create form.
    $this->myForm = new CoreForm();

    // Add field set.
    $field_set = $this->myForm->createFieldSet();

    // Create factory.
    $factory = new SystemModuleUpdateCompaniesSlatControlFactory($this->myLanId);
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = $button->createFormControl('submit', 'submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_UPDATE));
    $this->myForm->addSubmitHandler($button, 'handleForm');

    // Put everything together in a LouverControl.
    $louver = new LouverControl('data');
    $louver->setAttrClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($pages);
    $louver->populate();

    // Add the lover control to the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit, i.e. add or removes pages to the functionality.
   */
  private function dataBaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (!$changes) return;

    foreach ($changes['data'] as $cmp_id => $dummy)
    {
      if ($values['data'][$cmp_id]['mdl_granted'])
      {
        Abc::$DL->companyModuleEnable($cmp_id, $this->myModId);
      }
      else
      {
        Abc::$DL->companyModuleDisable($cmp_id, $this->myModId);
      }
    }

    // Use brute force to proper profiles.
    Abc::$DL->profileProper();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->myForm->execute();
    switch ($method)
    {
      case null;
        // Nothing to do.
        break;

      case  'handleForm':
        $this->handleForm();
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->databaseAction();

    Http::redirect(ModuleDetailsPage::getUrl($this->myModId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showModule()
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the module.
    NumericTableRow::addRow($table, 'ID', $this->myDetails['mdl_id'], '%d');

    // Add row for the module name.
    TextTableRow::addRow($table, 'Module', $this->myDetails['mdl_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
