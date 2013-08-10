<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'set/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlValidatorIntegerTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control (which must be a valid inter) values.
   */
  private function SetupForm1()
  {
    $form = new set_HtmlForm();

    $fieldset = $form->CreateFieldSet();

    $control = $fieldset->CreateFormControl( 'text', 'integer' );
    $control->AddValidator( new SET_HtmlFormControlValidatorInteger() );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control (which must be a valid inter) values.
   */
  private function SetupForm2()
  {
    $form = new set_HtmlForm();

    $fieldset = $form->CreateFieldSet();

    $control = $fieldset->CreateFormControl( 'text', 'integer' );
    $control->AddValidator( new SET_HtmlFormControlValidatorInteger( -1, 10 ) );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid uses values integer.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A zero must be valid.
   */
  public function testValidInteger1()
  {
    $_POST['integer'] = 0;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer (posted as string) must be valid.
   */
  public function testValidInteger2()
  {
    $_POST['integer'] = '56';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer (posted as integer) must be valid.
   */
  public function testValidInteger3()
  {
    $_POST['integer'] = 37 ;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An neagtive integer (posted as string) must be valid.
   */
  public function testValidInteger4()
  {
    $_POST['integer'] = '-11' ;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An neagtive integer (posted as integer) must be valid.
   */
  public function testValidInteger5()
  {
    $_POST['integer'] = -45 ;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** The test for valid range.
   */
  //--------------------------------------------------------------------------------------------------------------------
  /** An minimal integer is within a predetermined range must be valid.
   */
  public function testValidInteger6()
  {
    $_POST['integer'] = '-1' ;
    $form = $this->SetupForm2();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** If zero is within a predetermined range must be valid.
   */
  public function testValidInteger7()
  {
    $_POST['integer'] = '0' ;
    $form = $this->SetupForm2();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer is within a predetermined range must be valid.
   */
  public function testValidInteger8()
  {
    $_POST['integer'] = '3' ;
    $form = $this->SetupForm2();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An maximum integer is within a predetermined range must be valid.
   */
  public function testValidInteger9()
  {
    $_POST['integer'] = '10' ;
    $form = $this->SetupForm2();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}
  /** @name InvalidTests
      Test for invalid uses values non integer.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A string must be invalid.
   */
  public function testInvalidInteger1()
  {
    $_POST['integer'] = 'string';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A float must be invalid.
   */
  public function testInvalidInteger2()
  {
    $_POST['integer'] = '0.1';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mix aof numeric and alpha numeric must be invalid.
   */
  public function testInvalidInteger3()
  {
    $_POST['integer'] = '123abc'; // My favorite password ;-)
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** The test for invalid range.
   */
  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger4()
  {
    $_POST['integer'] = '-9';
    $form = $this->SetupForm2();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger5()
  {
    $_POST['integer'] = '-2';
    $form = $this->SetupForm2();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger6()
  {
    $_POST['integer'] = '11';
    $form = $this->SetupForm2();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger7()
  {
    $_POST['integer'] = '23';
    $form = $this->SetupForm2();

    $this->assertFalse( $form->Validate() );
  }
  //--------------------------------------------------------------------------------------------------------------------
  //@}
  /**
   */
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

