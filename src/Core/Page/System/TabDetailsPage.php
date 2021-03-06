<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableColumn\System\PageDetailsIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with information about a page group.
 */
class TabDetailsPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the page group shown on this page.
   *
   * @var int
   */
  protected $myTabId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->myTabId = self::getCgiVar('ptb', 'ptb');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $theTabId The ID of the tab shown on this page.
   *
   * @return string
   */
  public static function getUrl($theTabId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_TAB_DETAILS, 'pag');
    $url .= self::putCgiVar('ptb', $theTabId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showDetails();

    $this->showMasterPages();

    // XXX Show all pages.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of the page group.
   */
  private function showDetails()
  {
    $details = Abc::$DL->systemTabGetDetails($this->myTabId, $this->myLanId);
    $table   = new CoreDetailTable();

    // Add row with the ID of the tab.
    NumericTableRow::addRow($table, 'ID', $details['ptb_id'], '%d');

    // Add row with the title of the tab.
    TextTableRow::addRow($table, 'Title', $details['ptb_title']);

    // Add row with the label of the tab.
    TextTableRow::addRow($table, 'Label', $details['ptb_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview of all master pages of the page group.
   */
  private function showMasterPages()
  {
    $pages = Abc::$DL->systemTabGetMasterPages($this->myTabId, $this->myLanId);

    $table = new CoreOverviewTable($this->myCmpId, $this->myUsrId);

    // Show page ID.
    $table->addColumn(new NumericTableColumn('ID', 'pag_id'));

    // Show class name.
    $col = $table->addColumn(new TextTableColumn('Class', 'pag_class'));
    $col->sortOrder(1);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    // Show viewing the details the page.
    $table->addColumn(new PageDetailsIconTableColumn($this->myLanId));

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

