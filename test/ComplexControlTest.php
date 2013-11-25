<?php
//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ButtonControlTest
 */
class ComplexControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  // for exception
  public function testFindFormControlByName()
  {
    $form = $this->setForm1();

    // Real name.
    $control = $form->findFormControlByName( 'street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );

    // Name not exist.
    $control = $form->findFormControlByName( 'notexists' );
    $this->assertEquals( null, $control );

    // Use path not name.
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

    $control = $form->findFormControlByPath( '/post/zip-code' );
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
  /**
   * Use real name.
   */
  public function testGetFormControlByName()
  {
    $form = $this->setForm1();

    // Real name.
    $control = $form->getFormControlByName( 'street' );
    $this->assertInstanceOf( '\SetBased\Html\Form\Control\Control', $control );
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Use real path.
   */
  public function testGetFormControlByPath()
  {
    $form = $this->setForm1();

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
  /**
   *  Use wrong name.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName1()
  {
    $form = $this->setForm1();
    $form->getFormControlByName( 'notexists' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Use path, not name.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName2()
  {
    $form = $this->setForm1();
    $form->getFormControlByName( '/nopath/notexists' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Use path, not name.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName3()
  {
    $form = $this->setForm1();
    $form->getFormControlByName( '/vacationh/notexists' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Use wrong path.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath1()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath( '/notexists' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Use name, not path.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath2()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath( 'street' );
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Use wrong path.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath3()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath( '/nopath/notexists' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Use wrong path.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath4()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath( '/vacationh/notexists' );
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

    $complex = $fieldset->createFormControl( 'complex', 'post' );
    $complex->createFormControl( 'text', 'zip-code' );
    $complex->createFormControl( 'text', 'state' );

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
