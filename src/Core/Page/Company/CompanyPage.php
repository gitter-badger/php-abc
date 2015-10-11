<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for pages about companies.
 */
abstract class CompanyPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the company of which data is shown on this page (i.e. the target company).
   *
   * @var int
   */
  protected $myActCmpId;

  /**
   * The details of the company of which data is shown on this page.
   *
   * @var array
   */
  protected $myCompanyDetails;

  /**
   * Form for selecting the company.
   *
   * @var CoreForm
   */
  private $myCompanyForm;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myActCmpId = self::getCgiVar('cmp', 'cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL to a child page of this page.
   *
   * @param int $thePagId The ID of the child page.
   * @param int $theCmpId The ID of the target company.
   *
   * @return string The URL.
   */
  public static function getChildUrl($thePagId, $theCmpId)
  {
    $url = '/pag/'.Abc::obfuscate($thePagId, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows brief information about the target company.
   */
  protected function echoDashboard()
  {
    // Return immediately if the cmp_id is not set.
    if (!$this->myActCmpId) return;

    $this->myCompanyDetails = Abc::$DL->companyGetDetails($this->myActCmpId);

    echo '<div id="dashboard">';
    echo '<div id="info">';

    echo '<div id="info0">';
    echo Html::txt2html($this->myCompanyDetails['cmp_abbr']);
    echo '<br/>';
    echo '<br/>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    if ($this->myActCmpId)
    {
      $this->appendPageTitle($this->myCompanyDetails['cmp_abbr']);
    }
    else
    {
      $this->getCompany();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getTabUrl($thePagId)
  {
    if ($this->myActCmpId || $thePagId==C::PAG_ID_COMPANY_OVERVIEW)
    {
      return CompanyPage::getChildUrl($thePagId, $this->myActCmpId);
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form for selecting the target company.
   */
  private function createCompanyForm()
  {
    $this->myCompanyForm = new CoreForm();

    // Create input control for Company abbreviation.
    $input = $this->myCompanyForm->createFormControl('text', 'cmp_abbr', 'Company', true);
    $input->setAttrMaxLength(C::LEN_CMP_ABBR);

    // Create "OK" submit button.
    $this->myCompanyForm->addButtons();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the target company.
   */
  private function getCompany()
  {
    $abc = Abc::getInstance();

    $this->createCompanyForm();

    if ($this->myCompanyForm->isSubmitted('submit'))
    {
      $this->myCompanyForm->loadSubmittedValues();
      $valid = $this->myCompanyForm->validate();
      if (!$valid)
      {
        $this->showCompanyForm();
      }
      else
      {
        $values           = $this->myCompanyForm->getValues();
        $this->myActCmpId = Abc::$DL->companyGetCmpIdByCmpAbbr($values['cmp_abbr']);
        if ($this->myActCmpId) Http::redirect(CompanyPage::getChildUrl($abc->getPagId(), $this->myActCmpId));
        else $this->showCompanyForm();
      }
    }
    else
    {
      $this->showCompanyForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the form for selecting the target company.
   */
  private function showCompanyForm()
  {
    echo $this->myCompanyForm->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

