<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'lib/form.php' );

//----------------------------------------------------------------------------------------------------------------------
/** @brief Abstract super class for test for @c SET_HtlmFormControlText, @c SET_HtlmFormControlHidden,
           @c SET_HtlmFormControlPassword.
 */
abstract class SET_HtmlFormControlSimpleTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function getInputType();

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control.
   */
  private function SetupForm1( $theValue )
  {
    $form = new SET_HtmlForm();
    $fieldset= $form->createFieldSet();

    $control = $fieldset->createFormControl( $this->getInputType(), 'name' );
    if (isset($theValue)) $control->setAttribute( 'value', $theValue );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid submitted values.
   */
  //@{
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
  /** Test a submitted empty value.
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
  //@}
  /** @name EmptyTests
      Tests for submitted values for which @c empty return true.
   */
  //@{
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
  //@}

  //--------------------------------------------------------------------------------------------------------------------
}