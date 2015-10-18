<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Helper\Http;
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for deleting a company specific page that overrides a standard page.
 */
class SpecificPageDeletePage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myTargetCmpId = self::getCgiVar('cmp', 'cmp');

    $this->myTargetPagId = self::getCgiVar('tar_pag', 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $theCmpId       The ID of the target company.
   * @param int $theTargetPagId The ID of the page the must be deleted.
   *
   * @return string
   */
  public static function getUrl($theCmpId, $theTargetPagId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_DELETE, 'pag');
    $url .= self::putCgiVar('cmp', $theCmpId, 'cmp');
    $url .= self::putCgiVar('tar_pag', $theTargetPagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Deletes a company specific page.
   */
  public function echoPage()
  {
    Abc::$DL->companySpecificPageDelete($this->myTargetCmpId, $this->myTargetPagId);

    Http::redirect(SpecificPageOverviewPage::getUrl($this->myTargetCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
