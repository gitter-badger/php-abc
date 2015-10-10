<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordDeletePage;
use SetBased\Abc\Core\TableColumn\DeleteIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon to delete a word.
 */
class WordDeleteIconTableColumn extends DeleteIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theRow
   *
   * @return string
   */
  public function getUrl($theRow)
  {
    $this->myConfirmMessage = 'Remove word '.$theRow['wrd_id'].'?'; // xxxbbl

    return WordDeletePage::getUrl($theRow['wrd_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
