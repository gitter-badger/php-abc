<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page;

use SetBased\Abc\Abc;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for all core pages of ABC.
 */
abstract class CorePage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set disabled tabs (i.e. tabs in $myTabs field 'url' is empty) are shown. Otherwise, disabled tabs are hidden.
   */
  protected $myShowDisabledTabs = true;

  /**
   * If set the tab content is shown.
   *
   * @var bool
   */
  protected $myShowTabContent = false;

  /**
   * The tabs of the core page.
   *
   * @var array[]
   */
  protected $myTabs;

  /**
   * If set the JQuery UI date picker will be loaded.
   *
   * @var bool
   */
  private $myDatePickerEnabled = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->cssAppendSource('/css/abc/reset.css');
    $this->cssAppendSource('/css/abc/layout.css');
    $this->cssAppendSource('/css/abc/main-menu.css');
    $this->cssAppendSource('/css/abc/secondary-menu.css');
    $this->cssAppendSource('/css/abc/dashboard.css');
    $this->cssAppendSource('/css/abc/content.css');
    $this->cssAppendSource('/css/abc/style.css');
    $this->cssAppendSource('/css/abc/overview_table.css');
    $this->cssAppendSource('/css/abc/detail_table.css');
    $this->cssAppendSource('/css/abc/input_table.css');

    $this->jsAdmSetPageSpecificMain(__CLASS__);

    if (isset($_SERVER['ABC_ENV']) && $_SERVER['ABC_ENV']=='dev')
    {
      $this->enableW3cValidator();
    }

    Abc::getInstance()->setPageTitle(Abc::getInstance()->getPageGroupTitle().
                                     ' - '.
                                     Abc::getInstance()->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content, i.e. the inner HTML of the body tag.
   */
  public function echoPage()
  {
    // Buffer for actual contents.
    ob_start();

    $this->echoMainContent();

    $contents = ob_get_contents();
    ob_end_clean();

    // Buffer for header.
    ob_start();

    $this->echoPageLeader();

    // Show the actual content of the page.
    echo '<div id="main-content">';
    echo $contents;
    echo '</div>';

    // Show the menu.
    echo '<nav id="main-menu">';
    $this->echoMainMenu();
    echo '</nav>';

    $this->echoPageTrailer();

    // Write the HTML code of this page to the file system for (asynchronous) validation.
    if ($this->myW3cValidate)
    {
      file_put_contents($this->myW3cPathName, ob_get_contents());
    }

    $this->setPageSize(ob_get_length());
    ob_end_flush();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Can be overridden to echo a summary of the entity shown of the current page.
   */
  protected function echoDashboard()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the main content of the page, e.g. the dashboard, the tabs (secondary menu), and tab content.
   */
  protected function echoMainContent()
  {
    $this->echoDashboard();

    $this->showIconBar();

    echo '<nav id="secondary-menu" class="clearfix">';
    $this->echoTabs();
    echo '</nav>';

    echo '<div id="content">';
    $this->echoTabContent();
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function showIconBar()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content.
   */
  abstract protected function echoTabContent();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the tabs of this page, a.k.a. the secondary menu.
   */
  protected function echoTabs()
  {
    $pag_id_org = Abc::getInstance()->getPagIdOrg();

    $this->getPageTabs();

    echo '<ul>';
    foreach ($this->myTabs as &$tab)
    {
      if (isset($tab['url']))
      {
        $class = ($tab['pag_id']==$pag_id_org) ? $class = "class='selected'" : '';
        echo '<li><a href="', $tab['url'], '" ', $class, '>', Html::txt2html($tab['tab_name']), '</a></li>';
      }
      else
      {
        if ($this->myShowDisabledTabs) echo '<li><a class="disabled">', Html::txt2html($tab['tab_name']), '</a></li>';
      }

      if ($tab['pag_id']==$pag_id_org && $tab['url']) $this->myShowTabContent = true;
    }
    echo '</ul>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enables JQuery UI date pickers.
   */
  protected function enableDatePicker()
  {
    if (!$this->myDatePickerEnabled)
    {
      $this->jsAdmFunctionCall('SetBased/Abc/Page/Page', 'enableDatePicker');

      $this->cssAppendSource('/css/ui-lightness/jquery-ui-1.10.4.custom.min.css');

      $this->myDatePickerEnabled = true;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Retrieves the tabs of page group of the current page.
   */
  protected function getPageTabs()
  {
    $this->myTabs = Abc::$DL->authGetPageTabs($this->myCmpId,
                                              Abc::getInstance()->getPtbId(),
                                              $this->myProId,
                                              $this->myLanId);
    foreach ($this->myTabs as &$tab)
    {
      $tab['url'] = $this->getTabUrl($tab['pag_id']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of a tab of the page group of current page.
   *
   * @param int $thePagId The ID of the page of the tab.
   *
   * @return string
   */
  protected function getTabUrl($thePagId)
  {
    $url = self::putCgiVar('pag', $thePagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the main menu.
   */
  private function echoMainMenu()
  {
    $menu_items  = Abc::$DL->authGetMenu($this->myCmpId, $this->myProId, $this->myLanId);
    $page_mnu_id = Abc::getInstance()->getMnuId();

    // $logo_html = $this->myConfig->getValue( MMM_CFG_ID_RELATIONS_LOGO );
    // if ($logo_html) echo $logo_html;

    echo '<ul>';

    $last_group = 0;
    foreach ($menu_items as $i => $menu_item)
    {
      $mnu_link = '/pag/'.Abc::obfuscate($menu_item['pag_id'], 'pag').$menu_item['mnu_link'];
      $class    = "class='menu_".$menu_item['mnu_level'];

      if ($i==0) $class .= ' first';
      if ($i==count($menu_items) - 1) $class .= ' last';
      if ($menu_item['mnu_id']==$page_mnu_id) $class .= ' menu_active';
      if ($menu_item['mnu_group']<>$last_group) $class .= ' group_first';
      if (!isset($menu_items[$i + 1]) ||
        $menu_item['mnu_group']<>$menu_items[$i + 1]['mnu_group']
      ) $class .= ' group_last';

      $class .= "'";

      if ($mnu_link) echo "<li $class><a href='$mnu_link'>", Html::txt2html($menu_item['mnu_text']), "</a></li>";
      else           echo "<li $class><a>", Html::txt2html($menu_item['mnu_text']), "</a></li>";

      $last_group = $menu_item['mnu_group'];
    }
    echo '</ul>';

    // Define a content area for the feed back of w3c-validator.
    if ($this->myW3cValidate)
    {
      echo '<div id="w3c_validate"></div>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
