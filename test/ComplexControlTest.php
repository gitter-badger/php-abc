<?php
//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ButtonControlTest
 */
class ComplexControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testFindFormControlByName()
  {
    $form = $this->setForm1();

    // Real name.
    $control = $form->findFormControlByName( 'street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    // Name not exist.
    $control = $form->findFormControlByName( 'notexists' );
    $this->assertEquals( null, $control );

    $control = $form->findFormControlByName( '/nopath/notexists' );
    $this->assertEquals( null, $control );

    $control = $form->findFormControlByName( '/vacationh/notexists' );
    $this->assertEquals( null, $control );

  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testFindFormControlByPath()
  {
    $form = $this->setForm1();

    // Real path.
    $control = $form->findFormControlByPath( '/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->findFormControlByPath( '/post/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->findFormControlByPath( '/vacation/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->findFormControlByPath( '/vacation/post/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->findFormControlByPath( '/vacation/post/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );


    // Path not exist.
    $control = $form->findFormControlByPath( '/notexists' );
    $this->assertEquals( null, $control );

    $control = $form->findFormControlByPath( '/nopath/notexists' );
    $this->assertEquals( null, $control );

    $control = $form->findFormControlByPath( '/vacationh/notexists' );
    $this->assertEquals( null, $control );

  }

  //--------------------------------------------------------------------------------------------------------------------
  public function _testGetFormControlByName1()
  {
    $form = $this->setForm1();

    // Real name.
    $control = $form->getFormControlByName( 'street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function _testGetFormControlByPath1()
  {
    $form = $this->setForm1();

    // Real path.
    $control = $form->getFormControlByPath( '/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->getFormControlByPath( '/post/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->getFormControlByPath( '/vacation/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->getFormControlByPath( '/vacation/post/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    $control = $form->getFormControlByPath( '/vacation/post/street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );
  }

  //--------------------------------------------------------------------------------------------------------------------
  // for exception
  public function testGetFormControlByName2()
  {
    $form = $this->setForm1();

  }

  //--------------------------------------------------------------------------------------------------------------------
  // for exception
  public function testGetFormControlByPath2()
  {
    $form = $this->setForm1();

  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm1()
  {
    $form     = new SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $complex  = $fieldset->createFormControl( 'complex', '' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    $complex = $fieldset->createFormControl( 'complex', 'post' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    $fieldset = $form->createFieldSet( 'fieldset', 'vacation' );
    $complex  = $fieldset->createFormControl( 'complex', '' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    $complex = $fieldset->createFormControl( 'complex', 'post' );
    $complex->createFormControl( 'text', 'street' );
    $complex->createFormControl( 'text', 'city' );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
