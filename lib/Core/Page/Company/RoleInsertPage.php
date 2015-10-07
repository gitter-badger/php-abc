<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a new role.
 */
class RoleInsertPage extends RoleBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($theCmpId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_ROLE_INSERT, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a new role.
   */
  protected function databaseAction()
  {
    $values = $this->myForm->getValues();

    $this->myRolId = Abc::$DL->companyRoleInsert($this->myActCmpId, $values['rol_name'], $values['rol_weight']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

