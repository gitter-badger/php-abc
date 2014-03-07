<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class FormTest
 */
class FormTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars1()
  {
    $_POST = array();

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars( $changed );

    $this->assertFalse( $has_scalars );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars2()
  {
    $_POST          = array();
    $_POST['name1'] = 'Hello world';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars( $changed );

    $this->assertTrue( $has_scalars );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars3()
  {
    $_POST              = array();
    $_POST['name1']     = 'Hello world';
    $_POST['option'][2] = 'on';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars( $changed );

    $this->assertTrue( $has_scalars );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars4()
  {
    $_POST              = array();
    $_POST['option'][2] = 'on';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed     = $form->getChangedControls();
    $has_scalars = $form->hasScalars( $changed );

    $this->assertFalse( $has_scalars );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return Form
   */
  private function setupForm1()
  {
    $options   = array();
    $options[] = array('id' => 1, 'label' => 'label1');
    $options[] = array('id' => 2, 'label' => 'label2');
    $options[] = array('id' => 2, 'label' => 'label3');

    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $fieldset->createFormControl( 'text', 'name1' );
    $fieldset->createFormControl( 'text', 'name2' );

    $control = $fieldset->createFormControl( 'checkboxes', 'options' );
    $control->setOptions( $options, 'id', 'label' );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
