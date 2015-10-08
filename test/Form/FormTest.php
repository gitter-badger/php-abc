<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class FormTest
 */
class FormTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for getCurrentValues values.
   */
  public function testGetSetValues()
  {
    $options   = array();
    $options[] = array('id' => 1, 'label' => 'label1');
    $options[] = array('id' => 2, 'label' => 'label2');
    $options[] = array('id' => 3, 'label' => 'label3');

    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $fieldset->createFormControl( 'text', 'name1' );
    $fieldset->createFormControl( 'text', 'name2' );

    $control = $fieldset->createFormControl( 'checkboxes', 'options' );
    $control->setOptions( $options, 'id', 'label' );

    $values['name1']      = 'name1';
    $values['name2']      = 'name2';
    $values['options'][1] = true;
    $values['options'][2] = false;
    $values['options'][3] = true;

    // Set the form control values.
    $form->setValues( $values );

    $current = $form->getSetValues();

    $this->assertEquals( 'name1', $current['name1'] );
    $this->assertEquals( 'name2', $current['name2'] );
    $this->assertTrue( $current['options'][1] );
    $this->assertFalse( $current['options'][2] );
    $this->assertTrue( $current['options'][3] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex control with different types of names.
   */
  public function testFindComplexControl()
  {
    $names = array('hello', 10, 0, '0', '0.0');

    foreach ($names as $name)
    {
      $form = $this->setupFormFind( '', $name );

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
      $form = $this->setupFormFind( $name );

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
      $form = $this->setupFormFind( '', 'post', $name );

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
   * Test for merging values.
   */
  public function testMergeValues()
  {
    $options   = array();
    $options[] = array('id' => 1, 'label' => 'label1');
    $options[] = array('id' => 2, 'label' => 'label2');
    $options[] = array('id' => 3, 'label' => 'label3');

    $form     = new Form();
    $fieldset = $form->createFieldSet( 'fieldset', 'name' );

    $fieldset->createFormControl( 'text', 'name1' );
    $fieldset->createFormControl( 'text', 'name2' );

    $control = $fieldset->createFormControl( 'checkboxes', 'options' );
    $control->setOptions( $options, 'id', 'label' );

    $values['name']['name1']      = 'name1';
    $values['name']['name2']      = 'name2';
    $values['name']['options'][1] = true;
    $values['name']['options'][2] = false;
    $values['name']['options'][3] = true;

    $merge['name']['name1']      = 'NAME1';
    $merge['name']['options'][2] = true;
    $merge['name']['options'][3] = null;

    // Set the form control values.
    $form->setValues( $values );

    // Override few form control values.
    $form->mergeValues( $merge );

    // Generate HTML.
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML( $html );
    $xpath = new DOMXpath( $doc );

    // name[name1] must be overridden.
    $list = $xpath->query( "/form/fieldset/input[@name='name[name1]' and @value='NAME1']" );
    $this->assertEquals( 1, $list->length );

    // name[name2] must be unchanged.
    $list = $xpath->query( "/form/fieldset/input[@name='name[name2]' and @value='name2']" );
    $this->assertEquals( 1, $list->length );

    // name[options][1] must be unchanged.
    $list = $xpath->query( "/form/fieldset/span/input[@name='name[options][1]' and @checked='checked']" );
    $this->assertEquals( 1, $list->length );

    // name[options][2] must be changed.
    $list = $xpath->query( "/form/fieldset/span/input[@name='name[options][2]' and @checked='checked']" );
    $this->assertEquals( 1, $list->length );

    // name[options][3] must be changed.
    $list = $xpath->query( "/form/fieldset/span/input[@name='name[options][3]' and not(@checked)]" );
    $this->assertEquals( 1, $list->length );
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
  private function setupFormFind( $theFieldSetName = 'vacation',
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
