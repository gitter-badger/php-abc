<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Babel;

use SetBased\Abc\Core\Page\Babel\WordTranslateWordsPage;
use SetBased\Abc\Core\TableAction\TableAction;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action action for translation all words in a word group.
 */
class WordTranslateWordsTableAction implements TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The title of the icon of the table action.
   *
   * @var string
   */
  protected $myTitle;

  /**
   * The URL of the table action.
   *
   * @var string
   */
  protected $myUrl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theWdgId       The ID of the word group of the new word.
   * @param int $theTargetLanId The ID of the target language.
   */
  public function __construct($theWdgId, $theTargetLanId)
  {
    $this->myUrl = WordTranslateWordsPage::getUrl($theWdgId, $theTargetLanId);

    $this->myTitle = 'Translate words';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtml()
  {
    $ret = '<a';
    $ret .= Html::generateAttribute('href', $this->myUrl);
    $ret .= '<img';
    $ret .= Html::generateAttribute('title', $this->myTitle);
    $ret .= Html::generateAttribute('src=', ICON_BABEL_FISH );
    $ret .= ' width="16" height="16" alt="translate"/></a>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
