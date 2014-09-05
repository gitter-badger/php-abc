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
   * Test for finding a complex control with different types of names.
   */
  public function testFindComplexControl()
  {
    $names = array('hello', 10, 0, '0', '0.0');

    foreach ($names as $name)
    {
      $form = $this->setupForm2( '', $name );

      $control = $form->findFormControlByName( $name );
      $this->assertNotEmpty( $control );
      $this->assertEquals( $name, $control->getLocalName() );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a fieldset with different types of names.
   */
  public function testFindFieldSet()
  {
    $names = array('hello', 10, 0, '0', '0.0');

    foreach ($names as $name)
    {
      $form = $this->setupForm2( $name );

      $control = $form->findFormControlByName( $name );
      $this->assertNotEmpty( $control );
      $this->assertEquals( $name, $control->getLocalName() );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex with with different types of names.
   */
  public function testFindSimpleControl()
  {
    $names = array('hello', 10, 0, '0', '0.0');

    foreach ($names as $name)
    {
      $form = $this->setupForm2( '', 'post', $name );

      $control = $form->findFormControlByName( $name );
      $this->assertNotEmpty( $control );
      $this->assertEquals( $name, $control->getLocalName() );
    }
  }

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
  /**
   * @param string $theFieldSetName
   * @param string $theComplexControlName
   * @param string $theTextControlName
   *
   * @return Form
   */
  private function setupForm2( $theFieldSetName = 'vacation',
                               $theComplexControlName = 'post',
                               $theTextControlName = 'city'
  )
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet( 'fieldset', $theFieldSetName );
    $complex  = $fieldset->createFormControl( 'complex', '' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    $complex = $fieldset->createFormControl( 'complex', 'post' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    $complex = $fieldset->createFormControl( 'complex', 'post' );
    $complex->createFormControl( 'text', 'zip-code' );
    $complex->createFormControl( 'text', 'state' );

    $complex = $fieldset->createFormControl( 'complex', 'post' );
    $complex->createFormControl( 'text', 'zip-code' );
    $complex->createFormControl( 'text', 'state' );

    $fieldset = $form->createFieldSet( 'fieldset', 'vacation' );
    $complex  = $fieldset->createFormControl( 'complex', '' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    $complex = $fieldset->createFormControl( 'complex', $theComplexControlName );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', $theTextControlName );

    $complex2 = $complex->createFormControl( 'complex', '' );
    $complex2->createFormControl( 'text', 'street2' );
    $complex2->createFormControl( 'text', 'city2' );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
