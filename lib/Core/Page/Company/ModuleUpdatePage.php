<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\CompanyModulesUpdateSlatControlFactory;
use SetBased\Abc\Helper\Http;
use SetBased\Html\Form\Control\LouverControl;

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
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_MODULE_UPDATE, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();

    if ($this->myForm->isSubmitted('submit'))
    {
      $this->myForm->loadSubmittedValues();
      $valid = $this->myForm->validate();
      if (!$valid)
      {
        $this->echoForm();
      }
      else
      {
        $this->handleForm();

        Http::redirect(ModuleOverviewPage::getUrl($this->myActCmpId));
      }
    }
    else
    {
      $this->echoForm();
    }
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
    $this->myForm->setAttribute('class', 'input_table');

    // Add field set.
    $field_set = $this->myForm->createFieldSet();

    // Create factory.
    $factory = new CompanyModulesUpdateSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = $button->createFormControl('submit', 'submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_OK));

    // Put everything together in a LoverControl.
    $louver = new LouverControl('data');
    $louver->setAttribute('class', 'overview_table');
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
  private function handleForm()
  {
    $values  = $this->myForm->getValues();
    $changes = $this->myForm->getChangedControls();

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
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the form shown on this page.
   */
  private function echoForm()
  {
    echo $this->myForm->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
