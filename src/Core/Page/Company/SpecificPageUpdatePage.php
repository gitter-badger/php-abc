<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Helper\Http;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a company specific page that overrides a standard page.
 */
class SpecificPageUpdatePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

  /**
   * The details om the company specific page.
   *
   * @var array
   */
  private $myTargetPageDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->myTargetPagId = self::getCgiVar('tar_pag', 'pag');

    $this->myTargetPageDetails = Abc::$DL->companySpecificPageGetDetails($this->myActCmpId,
                                                                         $this->myTargetPagId,
                                                                         $this->myLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $theCmpId       The ID of the target company.
   * @param int $theTargetPagId The ID of the page.
   *
   * @return string The URL of this page.
   */
  public static function getUrl($theCmpId, $theTargetPagId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_SPECIFIC_PAGE_UPDATE, 'pag');
    $url .= '/cmp/'.Abc::obfuscate($theCmpId, 'cmp');
    $url .= '/tar_pag/'.Abc::obfuscate($theTargetPagId, 'pag');

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
        $this->handlePost();

        Http::redirect(SpecificPageOverviewPage::getUrl($this->myActCmpId));
      }
    }
    else
    {
      $this->echoForm();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the Company specific page after a form submit.
   */
  protected function handlePost()
  {
    if (!$this->myForm->getChangedControls()) return;

    $values = $this->myForm->getValues();

    Abc::$DL->companySpecificPageUpdate($this->myActCmpId, $this->myTargetPagId, $values['pag_class_child']);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->myForm = new CoreForm($this->myLanId);

    // Show the ID of the page.
    $control = $this->myForm->createFormControl('html', 'pag_id', 'ID');
    $control->setHtml($this->myTargetPageDetails['pag_id']);

    // Show the title of the page.
    $control = $this->myForm->createFormControl('html', 'pag_title', 'Title');
    $control->setHtml($this->myTargetPageDetails['pag_title']);

    // Show the parent class name of the page.
    $control = $this->myForm->createFormControl('html', 'pag_class_parent', 'Parent Class');
    $control->setHtml($this->myTargetPageDetails['pag_class_parent']);

    // Create text control for the child class name.
    $control = $this->myForm->createFormControl('text', 'pag_class_child', 'Child Class');
    $control->setValue($this->myTargetPageDetails['pag_class_child']);

    // Add a submit button
    $this->myForm->addButtons(Babel::getWord(C::WRD_ID_BUTTON_OK));
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
