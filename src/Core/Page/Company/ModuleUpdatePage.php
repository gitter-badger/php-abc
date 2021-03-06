<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\CompanyModulesUpdateSlatControlFactory;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for enabling and disabling the modules for a company.
 */
class ModuleUpdatePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $myForm;

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
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_MODULE_UPDATE, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    // Get all available modules.
    $modules = Abc::$DL->companyModuleGetAllAvailable($this->myActCmpId, $this->myLanId);

    // Create the form.
    $this->myForm = new CoreForm();

    // Add field set.
    $field_set = $this->myForm->createFieldSet();

    // Create factory.
    $factory = new CompanyModulesUpdateSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = $button->createFormControl('submit', 'submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_OK));
    $this->myForm->addSubmitHandler($button, 'handleForm');

    // Put everything together in a LoverControl.
    $louver = new LouverControl('data');
    $louver->setAttrClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($modules);
    $louver->populate();

    // Add the LouverControl the the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Handles the form submit.
   */
  private function databaseAction()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

    // If no changes are submitted return immediately.
    if (!$changes) return;

    foreach ($changes['data'] as $mdl_id => $dummy)
    {
      if ($values['data'][$mdl_id]['mdl_enabled'])
      {
        Abc::$DL->companyModuleEnable($this->myActCmpId, $mdl_id);
      }
      else
      {
        Abc::$DL->companyModuleDisable($this->myActCmpId, $mdl_id);
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

    Http::redirect(ModuleOverviewPage::getUrl($this->myActCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
