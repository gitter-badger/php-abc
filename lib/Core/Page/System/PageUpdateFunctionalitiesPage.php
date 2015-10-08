<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\SystemPageUpdateFunctionalitiesSlatControlFactory;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\DetailTable;
use SetBased\Abc\Core\TableRow\System\PageDetailsTableRow;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Helper\Http;
use SetBased\Html\TableRow\NumericTableRow;
use SetBased\Html\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for modifying the functionalities that grant access to a target page.
 */
class PageUpdateFunctionalitiesPage extends CorePage
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
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myTargetPagId = self::getCgiVar('tar_pag', 'pag');
    $this->myDetails     = Abc::$DL->systemPageGetDetails($this->myTargetPagId, $this->myLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $thePagId
   *
   * @return string
   */
  public static function getUrl($thePagId)
  {
    $url = '/pag/'.Abc::obfuscate(C::PAG_ID_SYSTEM_PAGE_UPDATE_FUNCTIONALITIES, 'pag');
    $url .= '/tar_pag/'.Abc::obfuscate($thePagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the functionalities that grant access to the target page.
   */
  protected function databaseAction()
  {
    $changes = $this->myForm->getChangedControls();
    $values  = $this->myForm->getValues();

    foreach ($changes['data'] as $fun_id => $dummy)
    {
      if ($values['data'][$fun_id]['fun_checked'])
      {
        Abc::$DL->systemFunctionalityInsertPage($fun_id, $this->myTargetPagId);
      }
      else
      {
        Abc::$DL->systemFunctionalityDeletePage($fun_id, $this->myTargetPagId);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showPageDetails();

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
        $this->databaseAction();

        Http::redirect(PageDetailsPage::getUrl($this->myTargetPagId));
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
    // Get all functionalities.
    $pages = Abc::$DL->systemPageGetAvailableFunctionalities($this->myTargetPagId, $this->myLanId);

    // Create form.
    $this->myForm = new CoreForm();
    $this->myForm->setAttribute('class', 'input_table');

    // Add field set.
    $field_set = $this->myForm->createFieldSet();

    // Create factory.
    $factory = new SystemPageUpdateFunctionalitiesSlatControlFactory($this->myLanId);
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = $button->createFormControl('submit', 'submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_OK));

    // Put everything together in a LouverControl.
    $louver = new LouverControl('data');
    $louver->setAttribute('class', 'overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($pages);
    $louver->populate();

    // Add the lover control to the form.
    $field_set->addFormControl($louver);
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
  /**
   * Echos the details of the target page.
   */
  private function showPageDetails()
  {
    $details = Abc::$DL->systemPageGetDetails($this->myTargetPagId, $this->myLanId);
    $table   = new DetailTable();

    // Add row with the ID of the page.
    NumericTableRow::addRow($table, 'ID', $details['pag_id'], '%d');

    // Add row with the title of the page.
    TextTableRow::addRow($table, 'Title', $details['pag_title']);

    // Add row with the tab name of the page.
    TextTableRow::addRow($table, 'Tab', $details['ptb_name']);

    // Add row with the ID of the parent page of the page.
    PageDetailsTableRow::addRow($table, 'Original Page', $details);

    // Add row with the menu item of the page.
    TextTableRow::addRow($table, 'Menu', $details['mnu_name']);

    // Add row with the class name of the page.
    TextTableRow::addRow($table, 'Class', $details['pag_class']);

    // Add row with the label of the page.
    TextTableRow::addRow($table, 'Label', $details['pag_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
