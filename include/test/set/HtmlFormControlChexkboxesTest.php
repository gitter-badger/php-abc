<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( './include/set/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlCheckboxesTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control.
   */
  private function SetupForm1()
  {
    $form = new SET_HtmlForm();
    $fieldset = $form->CreateFieldSet();
    $control = $fieldset->CreateFormControl( 'checkboxes', 'cnt_id' );

    $countries[] = array( 'cnt_id' => '0', 'cnt_name' => '-' );
    $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
    $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );

    $control->SetAttribute( 'set_map_key',     'cnt_id' );
    $control->SetAttribute( 'set_options',   $countries );

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
    $control = $fieldset->CreateFormControl( 'checkboxes', 'cnt_id' );

    $countries[] = array( 'cnt_id' => 0, 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => 1, 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => 2, 'cnt_name' => 'BE' );
    $countries[] = array( 'cnt_id' => 3, 'cnt_name' => 'LU' );

    $control->SetAttribute( 'set_map_key',      'cnt_id' );
    $control->SetAttribute( 'set_options',      $countries );
    $control->SetAttribute( 'set_map_checked',    '2' );

    $form->LoadSubmittedValues();

    return $form;
  }


  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
      Tests for valid submitted values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** Test a check/unchecked checkboxes are added correctly to the values.
   */
  public function testValid1()
  {
    $_POST['cnt_id']['3'] = 'on';

    $form = $this->SetupForm1();
    $values = $form->GetValues();

    // Test checkbox with index 3 has been checked.
    $this->assertTrue( $values['cnt_id']['3'] );

    // Test checkbox with index 1 has not been checked.
    $this->assertFalse( $values['cnt_id']['1'] );
  }

  //--------------------------------------------------------------------------------------------------------------------

  public function testValid2()
  {
    $_POST['cnt_id']['3'] = 'on';

    $form = $this->SetupForm2();
    $values = $form->GetValues();

    // Test checkbox with index 3 has been checked.
    $this->assertTrue( $values['cnt_id']['3'] );

    // Test checkbox with index 1 has not been checked.
    $this->assertFalse( $values['cnt_id']['1'] );
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
    $_POST['cnt_id']['99'] = 'on' ;

    $form    = $this->SetupForm1();
    $values  = $form->GetValues();
    $changed = $form->GetChangedControls() ;

    $this->assertArrayNotHasKey( 'cnt_id', $changed );
    $this->assertArrayNotHasKey( '99', $values['cnt_id'] );
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
    $_POST['cnt_id']['0'] = 'on';

    $form = $this->SetupForm1();
    $values = $form->GetValues();

    // Test checkbox with index 3 has been checked.
    $this->assertTrue( $values['cnt_id']['0'] );

    // Test checkbox with index 1 has not been checked.
    $this->assertFalse( $values['cnt_id']['1'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  //--------------------------------------------------------------------------------------------------------------------
}
