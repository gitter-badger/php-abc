<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'lib/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlValidatorEmailTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control (which must be a valid email address) values.
   */
  private function SetupForm1()
  {
    $form = new set_HtmlForm();

    $fieldset = $form->CreateFieldSet( 'fieldset' );

    $control = $fieldset->CreateFormControl( 'text', 'email' );
    $control->AddValidator( new SET_HtmlFormControlValidatorEmail() );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid email addresses.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A valid email address must be valid.
   */
  public function testValidEmail1()
  {
    $_POST['email'] = 'info@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A valid email address must be valid.
   */
  public function testValidEmail2()
  {
    $_POST['email'] = 'p.r.water@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A valid email address must be valid.
   */
  public function testValidEmail3()
  {
    $_POST['email'] = 'disposable.style.email.with+symbol@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Top-level domains are valid hostnames. However, inpractice they don't have MX records.
   */
  // public function testValidEmail4()
  // {
  //   $_POST['email'] = 'postbox@com';
  //   $form = $this->SetupForm1();
  //
  //   $this->assertTrue( $form->Validate() );
  // }

  //--------------------------------------------------------------------------------------------------------------------
  /** An email address with a long local part must be valid. The maximum length of the local part is 64 characters,
      see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testValidEmailWithLongLocalPart()
  {
    $local = str_repeat( 'x', 64 );
    $_POST['email'] = "$local@setbased.nl";
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An email address with a long domain part must be valid. The maximum length of the domain part is 255 characters,
      see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testValidEmailWithLongDomain()
  {
    $_POST['email'] = 'info@thelongestdomainnameintheworldandthensomeandthensomemoreandmore.com';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An empty email address is a valid email address.
   */
  public function testValidEmailNull()
  {
    $_POST['email'] = null;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An empty email address is a valid email address.
   */
  public function testValidEmailFalse()
  {
    $_POST['email'] = false;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An empty email address is a valid email address.
   */
  public function testValidEmailEmpty()
  {
    $_POST['email'] = '';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  /** @name InvalidTests
      Test for invalid email addresses.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** An unsual email address must be invalid.
   */
  public function testInvalidEmail1()
  {
    $_POST['email'] = '"much.more unusual"@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**  An unsual email address must be invalid.
   */
  public function testInvalidEmail2()
  {
    $_POST['email'] = '"very.unusual.@.unusual.com"@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A strange but valid email address must be valid.
   */
  public function testInvalidEmail3()
  {
    $_POST['email'] = '!#$%&\'*+-/=?^_`{}|~@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**  Localhost is not a valid domain part.
   */
  public function testInvalidEmail4()
  {
    $_POST['email'] = 'info@localhost';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An @ character must separate local and domain part.
   */
  public function testInvalidEmailWithoutAt()
  {
    $_POST['email'] = 'info.setbased.nl';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats1()
  {
    $_POST['email'] = 'info@info@setbased.nl';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats2()
  {
    $_POST['email'] = 'info@setbased.nl@info';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An email address without an existing A or MX record is invalid.
   */
  public function testInvalidEmailWithNoexitantDomain()
  {
    $_POST['email'] = 'info@xsetbased.nl';
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An email address with a to long local part must be invalid. The maximum length of the local part is 64 characters,
      see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testInvalidEmailWithToLongLocalPart()
  {
    $local = str_repeat( 'x', 65 );
    $_POST['email'] = "$local@setbased.nl";
    $form = $this->SetupForm1();

    $this->assertFalse( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   @}
   */
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

