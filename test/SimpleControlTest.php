<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Abstract super class for test for @c SET_HtlmFormControlText, @c SET_HtlmFormControlHidden,
 * @c SET_HtlmFormControlPassword.
 */
abstract class SimpleControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function getInputType();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control.
   */
  private function setupForm1( $theValue )
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( $this->getInputType(), 'name' );
    if (isset($theValue)) $control->setAttribute( 'value', $theValue );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Test a submitted value.
   */
  public function testValid101()
  {
    $name         = 'Set Based IT Consultancy';
    $_POST['name'] = $name;

    $form    = $this->setupForm1( null );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( $name, $values['name'] );
    $this->assertTrue( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted empty value.
   */
  public function testValid102()
  {
    $name         = 'Set Based IT Consultancy';
    $_POST['name'] = '';

    $form    = $this->setupForm1( $name );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEmpty( $values['name'] );
    $this->assertTrue( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Test a submitted value '0'.
   */
  public function test1Empty1()
  {
    $name         = 0;
    $_POST['name'] = $name;

    $form    = $this->setupForm1( null );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( $name, $values['name'] );
    $this->assertTrue( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Test a submitted value '0.0'.
   */
  public function test1Empty2()
  {
    $name         = '0.0';
    $_POST['name'] = $name;

    $form    = $this->setupForm1( '' );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( $name, $values['name'] );
    $this->assertTrue( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

