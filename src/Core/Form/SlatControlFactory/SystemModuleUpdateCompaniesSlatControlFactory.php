<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemModuleUpdateCompaniesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with company ID.
    $table_column = new NumericTableColumn('ID', 'cmp_id');
    $this->addSlatJoint('cmp_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with abbr of the company.
    $table_column = new TextTableColumn('Name', 'cmp_abbr');
    $table_column->sortOrder(1);
    $this->addSlatJoint('cmp_abbr', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for granting or revoking the module.
    $table_column = new CheckboxSlatJoint('Grant');
    $this->addSlatJoint('mdl_granted', $table_column);

    $this->myCmpIdObfuscator = abc::getObfuscator('cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($theLouverControl, $theData)
  {
    /** @var SlatControl $row */
    $row = $theLouverControl->addFormControl(new SlatControl($theData['cmp_id']));
    $row->setObfuscator($this->myCmpIdObfuscator);

    $control = $this->createFormControl($row, 'cmp_id');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'cmp_abbr');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'mdl_granted');
    $control->setValue($theData['mdl_granted']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
