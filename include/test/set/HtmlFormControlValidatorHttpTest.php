<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'set/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlValidatorHttpTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a text form control (which must be a valid url address).
   */
  private function SetupForm1()
  {
    $form = new set_HtmlForm();

    $fieldset = $form->CreateFieldSet( 'fieldset' );

    $control = $fieldset->CreateFormControl( 'text', 'url' );
    $control->AddValidator( new SET_HtmlFormControlValidatorHttp() );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid url addresses.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A valid url address must be valid.
   */
  public function testValidHttp()
  {
    $_POST['url'] = 'http://www.setbased.nl';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A valid url address must be valid.
   */
  public function testValidHttp2()
  {
    $_POST['url'] = 'http://www.google.com';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A valid url address must be valid.
   */
  public function testValidHttp3()
  {
    $_POST['url'] = 'http://www.php.net';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An empty url address is a valid url address.
   */
  public function testValidHttpNull()
  {
    $_POST['url'] = null;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An empty url address is a valid url address.
   */
  public function testValidHttpFalse()
  {
    $_POST['url'] = false;
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An empty url address is a valid url address.
   */
  public function testValidHttpEmpty()
  {
    $_POST['url'] = '';
    $form = $this->SetupForm1();

    $this->assertTrue( $form->Validate() );
  }
//--------------------------------------------------------------------------------------------------------------------
  //@}

  /** @name InvalidTests
      Test for invalid url addresses.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** An unsual url address must be invalid.
   */
  public function testInvalidHttp1()
  {
    $_POST['url'] = 'hffd//:www.setbased/nl';
    $form = $this->SetupForm1();

    $this->assertFalse($form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**  An unsual url address must be invalid.
   */
  public function testInvalidHttp2()
  {
    $_POST['url'] = 'http//golgelinva';
    $form = $this->SetupForm1();

    $this->assertFalse($form->Validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A strange but valid url address must be valid.
   */
  public function testInvalidHttp3()
 {
    $_POST['url'] = 'ftp//:!#$%&\'*+-/=?^_`{}|~ed.com';
    $form = $this->SetupForm1();

    $this->assertFalse($form->Validate() );
  }

   //--------------------------------------------------------------------------------------------------------------------
  /**
   @}
   */
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

