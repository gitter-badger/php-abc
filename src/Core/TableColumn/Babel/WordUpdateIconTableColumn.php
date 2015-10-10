<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for updating the details of a word.
 */
class WordUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($theRow)
  {
    return WordUpdatePage::getUrl($theRow['wrd_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
