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
 * Page for inserting a company specific page that overrides a standard page.
 */
class SpecificPageInsertPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $myForm;

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
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_COMPANY_SPECIFIC_PAGE_INSERT, 'pag');
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
   * Inserts a company specific page.
   */
  protected function handlePost()
  {
    $values = $this->myForm->getValues();

    Abc::$DL->companySpecificPageInsert($this->myActCmpId, $values['prt_pag_id'], $values['pag_class_child']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $pages = Abc::$DL->systemPageGetAll($this->myLanId);

    $this->myForm = new CoreForm($this->myLanId);

    $control = $this->myForm->createFormControl('select', 'prt_pag_id', 'Parent Class');
    $control->setOptions($pages, 'pag_id', 'pag_class');
    $control->setOptionsObfuscator(Abc::getObfuscator('pag'));

    $this->myForm->createFormControl('text', 'pag_class_child', 'Child Class');

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
