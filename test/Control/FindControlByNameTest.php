<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;
use SetBased\Html\Form\Control\TextControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class FindControlByNameTest
 */
class FindControlByNameTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var TextControl
   */
  private $myControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form with control in the form.
   */
  private function setForm1( $theNameOfControl )
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $this->myControl = $fieldset->createFormControl( 'text', $theNameOfControl );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form with control in the complex control.
   */
  private function setForm2( $theNameOfControl, $theNameOfComplexControl )
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $complex = $fieldset->createFormControl( 'complex', $theNameOfComplexControl );
    $this->myControl = $complex->createFormControl( 'text', $theNameOfControl );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with alphanumeric name of the control in the form.
   */
  public function testValid1()
  {
    $name_of_control = 'test01';

    $form    = $this->setForm1( $name_of_control );
    $control = $form->findFormControlByName( $name_of_control );

    $this->assertNotEmpty( $control );
    $this->assertEquals($this->myControl, $control);
    $this->assertEquals($name_of_control, $control->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with integer name of the control in the form.
   */
  public function testValid2()
  {
    $name_of_control = 10;

    $form    = $this->setForm1( $name_of_control );
    $control = $form->findFormControlByName( $name_of_control );

    $this->assertNotEmpty( $control );
    $this->assertEquals($this->myControl, $control);
    $this->assertEquals($name_of_control, $control->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with '0' name of the control in the form.
   */
  public function testValid3()
  {
    $name_of_control = 0;

    $form    = $this->setForm1( $name_of_control );
    $control = $form->findFormControlByName( $name_of_control );

    $this->assertNotEmpty( $control );
    $this->assertEquals($this->myControl, $control);
    $this->assertEquals($name_of_control, $control->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with alphanumeric name of the control in the complex control.
   */
  public function testValid4()
  {
    $name_of_control = 'test01';
    $name_of_complex_control = 'test';

    // Create form with control inside of complex control.
    $form = $this->setForm2( $name_of_control, $name_of_complex_control );

    // Firs find complex control by name.
    $complex_control = $form->findFormControlByName( $name_of_complex_control );

    // Find control by name.
    $control = $complex_control->findFormControlByName( $name_of_control );

    $this->assertNotEmpty( $control );
    $this->assertEquals($this->myControl, $control);
    $this->assertEquals($name_of_control, $control->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with integer name of the control in the  complex control.
   */
  public function testValid5()
  {
    $name_of_control = 10;
    $name_of_complex_control = 'test';

    // Create form with control inside of complex control.
    $form = $this->setForm2( $name_of_control, $name_of_complex_control );

    // Firs find complex control by name.
    $complex_control = $form->findFormControlByName( $name_of_complex_control );

    // Find control by name.
    $control = $complex_control->findFormControlByName( $name_of_control );

    $this->assertNotEmpty( $control );
    $this->assertEquals($this->myControl, $control);
    $this->assertEquals($name_of_control, $control->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with '0' name of the control in the  complex control.
   */
  public function testValid6()
  {
    $name_of_control = 0;
    $name_of_complex_control = 'test';

    // Create form with control inside of complex control.
    $form = $this->setForm2( $name_of_control, $name_of_complex_control );

    // Firs find complex control by name.
    $complex_control = $form->findFormControlByName( $name_of_complex_control );

    // Find control by name.
    $control = $complex_control->findFormControlByName( $name_of_control );

    $this->assertNotEmpty( $control );
    $this->assertEquals($this->myControl, $control);
    $this->assertEquals($name_of_control, $control->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------

}
//----------------------------------------------------------------------------------------------------------------------

