<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'lib/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlRadiosTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control.
   */
  private function SetupForm1()
  {
    $form = new SET_HtmlForm();
    $fieldset = $form->CreateFieldSet();
    $control = $fieldset->CreateFormControl( 'radios', 'cnt_id' );

    $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
    $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );

    $control->SetAttribute( 'set_map_key',     'cnt_id' );
    $control->SetAttribute( 'set_options',      $countries );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control. Difference between this function
      and SetupForm1 are the cnt_id are integers.
   */
  private function SetupForm2()
  {
    $form = new SET_HtmlForm();
    $fieldset = $form->CreateFieldSet();
    $control = $fieldset->CreateFormControl( 'radios', 'cnt_id' );

    $countries[] = array( 'cnt_id' => 1, 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => 2, 'cnt_name' => 'BE' );
    $countries[] = array( 'cnt_id' => 3, 'cnt_name' => 'LU' );

    $control->SetAttribute( 'set_map_key',      'cnt_id' );
    $control->SetAttribute( 'set_options',      $countries );
    $control->SetAttribute( 'set_value',    '1' );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid submitted values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid.
   */
  public function testValid1()
  {
    $_POST['cnt_id'] = '3';

    $form = $this->SetupForm1();
    $values = $form->GetValues();

    $this->assertEquals( '3', $values['cnt_id'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid (even whens tring and integers are mixed).
   */
  public function testValid2()
  {
    $_POST['cnt_id'] = '3';

    $form = $this->SetupForm2();
    $values = $form->GetValues();

    $this->assertEquals( '3', $values['cnt_id'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  /** @name WhiteListTests
      Tests for white listed values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** Only whitelisted values must be loaded.
  */
  public function testWhiteListed1()
  {
    // cnt_id is not a value that is in the whitelist of values (i.e. 1,2, and 3).
    $_POST['cnt_id'] = 99;

    $form = $this->SetupForm1();
    $values = $form->GetValues();

    $this->assertArrayHasKey( 'cnt_id', $values );
    $this->assertNull( $values['cnt_id'] );
    $this->assertEmpty( $form->GetChangedControls() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  //--------------------------------------------------------------------------------------------------------------------
}