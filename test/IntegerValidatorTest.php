<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class IntegerValidatorTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control (which must be a valid inter) values.
   */
  private function SetupForm1()
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'text', 'integer' );
    $control->addValidator( new \SetBased\Html\Form\IntegerValidator() );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control (which must be a valid inter) values.
   */
  private function SetupForm2()
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'text', 'integer' );
    $control->addValidator( new \SetBased\Html\Form\IntegerValidator( -1, 10 ) );

    $form->loadSubmittedValues();

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
    $form = $this->setupForm1();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer (posted as string) must be valid.
   */
  public function testValidInteger2()
  {
    $_POST['integer'] = '56';
    $form = $this->setupForm1();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer (posted as integer) must be valid.
   */
  public function testValidInteger3()
  {
    $_POST['integer'] = 37 ;
    $form = $this->setupForm1();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An neagtive integer (posted as string) must be valid.
   */
  public function testValidInteger4()
  {
    $_POST['integer'] = '-11' ;
    $form = $this->setupForm1();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An neagtive integer (posted as integer) must be valid.
   */
  public function testValidInteger5()
  {
    $_POST['integer'] = -45 ;
    $form = $this->setupForm1();

    $this->assertTrue( $form->validate() );
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
    $form = $this->setupForm2();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** If zero is within a predetermined range must be valid.
   */
  public function testValidInteger7()
  {
    $_POST['integer'] = '0' ;
    $form = $this->setupForm2();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer is within a predetermined range must be valid.
   */
  public function testValidInteger8()
  {
    $_POST['integer'] = '3' ;
    $form = $this->setupForm2();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An maximum integer is within a predetermined range must be valid.
   */
  public function testValidInteger9()
  {
    $_POST['integer'] = '10' ;
    $form = $this->setupForm2();

    $this->assertTrue( $form->validate() );
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
    $form = $this->setupForm1();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A float must be invalid.
   */
  public function testInvalidInteger2()
  {
    $_POST['integer'] = '0.1';
    $form = $this->setupForm1();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mix aof numeric and alpha numeric must be invalid.
   */
  public function testInvalidInteger3()
  {
    $_POST['integer'] = '123abc'; // My favorite password ;-)
    $form = $this->setupForm1();

    $this->assertFalse( $form->validate() );
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
    $form = $this->setupForm2();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger5()
  {
    $_POST['integer'] = '-2';
    $form = $this->setupForm2();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger6()
  {
    $_POST['integer'] = '11';
    $form = $this->setupForm2();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger7()
  {
    $_POST['integer'] = '23';
    $form = $this->setupForm2();

    $this->assertFalse( $form->validate() );
  }
  //--------------------------------------------------------------------------------------------------------------------
  //@}
  /**
   */
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

