<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'lib/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlValidatorMandatoryTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a single form control of type $theType.
   */
  private function SetupForm1( $theType )
  {
    $form = new set_HtmlForm();

    $fieldset = $form->createFieldSet( 'fieldset' );

    $control = $fieldset->createFormControl( $theType, 'input' );
    $control->addValidator( new SET_HtmlFormControlValidatorMandatory() );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a checkbox form control.
   */
  private function SetupForm2()
  {
    $form = new set_HtmlForm();

    $fieldset = $form->createFieldSet( 'fieldset' );

    $control = $fieldset->createFormControl( 'checkbox', 'box' );
    $control->addValidator( new SET_HtmlFormControlValidatorMandatory() );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid value.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory non-empty text, password, hiddem, or textarea form control is valid.
   */
  public function testValidNoneEmptyText()
  {
    $types = array( 'text', 'password', 'hidden', 'textarea' );

    foreach( $types as $i => $type )
    {
      $_POST['input'] = 'Set Based IT Consultancy';
      $form = $this->setupForm1( $type );

      $this->assertTrue( $form->validate(), sprintf( "type: '%s'.", $type )  );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory checked checkd box is valid.
   */
  public function testValidCheckedCheckbox()
  {
    $_POST['box'] = 'on';
    $form = $this->setupForm2();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  /** @name InvalidTests
      Test for invalid values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory text, password, hidden or textarea form control with value @c null, @c false, or @c '', is invalid.
   */
  public function testInvalidEmpty()
  {
    $types  = array( 'text', 'password', 'hidden', 'textarea', 'checkbox' );
    $values = array( null, false, '' );

    foreach( $types as $i => $type )
    {
      foreach( $values as $j => $value )
      {

        $_POST['input'] = $value;
        $form = $this->setupForm1( $type );

        $this->assertFalse( $form->validate(),
                            sprintf( "type: '%s', value: '%s'.", $type, var_export( $value, true ) ) );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory text, password, or textarea form control with whitespace is invalid.
   */
  public function testInvalidWhitespace()
  {
    $type   = array( 'text', 'password', 'textarea' );
    $values = array( ' ', '  ', " \n  " );

    foreach( $type as $i => $type )
    {
      foreach( $values as $j => $value )
      {

        $_POST['input'] = $value;
        $form = $this->setupForm1( $type );

        $this->assertFalse( $form->validate(),
                            sprintf( "type: '%s', value: '%s'.", $type, var_export( $value, true ) ) );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory unchecked checkd box is invalid.
   */
  public function testinvalidUncheckedCheckbox()
  {
    $_POST = array();
    $form = $this->setupForm2();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes
  //--------------------------------------------------------------------------------------------------------------------
  //@}
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

