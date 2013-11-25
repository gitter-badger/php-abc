<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/** @brief Abstract super class for test for @c \SetBased\Htlm\Form\TextControl, @c \SetBased\Htlm\Form\HiddenControl,
 *  @c \SetBased\Htlm\Form\PasswordControl.
 */
abstract class SimpleControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0'.
   */
  public function test1Empty1()
  {
    $name          = 0;
    $_POST['name'] = $name;

    $form    = $this->setupForm1( null );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( $name, $values['name'] );
    $this->assertNotEmpty( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0.0'.
   */
  public function test1Empty2()
  {
    $name          = '0.0';
    $_POST['name'] = $name;

    $form    = $this->setupForm1( '' );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( $name, $values['name'] );
    $this->assertNotEmpty( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( $this->getInputType(), 'name' );
    $control->setAttribute( 'value', '1' );

    $control->setPrefix( 'Hello' );
    $control->setPostfix( 'World' );
    $html = $form->Generate();

    $pos = strpos( $html, 'Hello<input' );
    $this->assertNotEquals( false, $pos );

    $pos = strpos( $html, '/>World' );
    $this->assertNotEquals( false, $pos );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value.
   */
  public function testValid101()
  {
    $name          = 'Set Based IT Consultancy';
    $_POST['name'] = $name;

    $form    = $this->setupForm1( null );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( $name, $values['name'] );
    $this->assertNotEmpty( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted empty value.
   */
  public function testValid102()
  {
    $name          = 'Set Based IT Consultancy';
    $_POST['name'] = '';

    $form    = $this->setupForm1( $name );
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEmpty( $values['name'] );
    $this->assertNotEmpty( $changed['name'] );
  }

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

}

//----------------------------------------------------------------------------------------------------------------------

