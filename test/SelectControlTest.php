<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class SelectControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control.
   */
  private function setupForm1()
  {
    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control = $fieldset->createFormControl( 'select', 'cnt_id' );

    $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
    $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );

    $control->setAttribute( 'set_map_key',     'cnt_id' );
    $control->setAttribute( 'set_options',      $countries );
    $control->setAttribute( 'set_empty_option', true );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control. Difference between this function and SetupForm1 are the cnt_id are
      integers.
   */
  private function setupForm2()
  {
    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control = $fieldset->createFormControl( 'select', 'cnt_id' );

    $countries[] = array( 'cnt_id' => 1, 'cnt_name' => 'NL' );
    $countries[] = array( 'cnt_id' => 2, 'cnt_name' => 'BE' );
    $countries[] = array( 'cnt_id' => 3, 'cnt_name' => 'LU' );

    $control->setAttribute( 'set_map_key',      'cnt_id' );
    $control->setAttribute( 'set_options',      $countries );
    $control->setAttribute( 'set_empty_option', true );
    $control->setAttribute( 'set_value',       ' 1' );

    $form->loadSubmittedValues();

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

    $form = $this->setupForm1();
    $values = $form->getValues();

    $this->assertEquals( '3', $values['cnt_id'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid (even whens tring and integers are mixed).
   */
  public function testValid2()
  {
    $_POST['cnt_id'] = '3';

    $form = $this->setupForm2();
    $values = $form->getValues();

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

    $form = $this->setupForm1();
    $values = $form->getValues();

    $this->assertArrayHasKey( 'cnt_id', $values );
    $this->assertNull( $values['cnt_id'] );
    $this->assertEmpty( $form->getChangedControls() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  //--------------------------------------------------------------------------------------------------------------------
}
