<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a company.
 */
class CompanyUpdatePage extends CompanyBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct()
  {
    parent::__construct();

    $this->myActCmpId = self::getCgiVar('cmp', 'cmp');

    $this->myDetails = Abc::$DL->companyGetDetails($this->myActCmpId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theCmpId The ID of the target language.
   *
   * @return string
   */
  public static function getUrl($theCmpId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_UPDATE, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the target company.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    // Return immediately if no changes are submitted.
    if (!$changes) return;

    Abc::$DL->companyUpdate($this->myActCmpId, $values['cmp_abbr'], $values['cmp_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $this->myForm->setValues($this->myDetails);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

