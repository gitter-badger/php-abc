<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class CheckboxesControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Test a check/unchecked checkboxes are added correctly to the values.
   */
  public function testValid1()
  {
    $_POST['cnt_id']['3'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 3 has been checked.
    $this->assertTrue( $values['cnt_id']['3'] );

    // Test checkbox with index 1 has not been checked.
    $this->assertFalse( $values['cnt_id']['1'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control.
   */
  private function setupForm1()
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'checkboxes', 'cnt_id' );

    $countries[] = array('cnt_id' => '0', 'cnt_name' => '-');
    $countries[] = array('cnt_id' => '1', 'cnt_name' => 'NL');
    $countries[] = array('cnt_id' => '2', 'cnt_name' => 'BE');
    $countries[] = array('cnt_id' => '3', 'cnt_name' => 'LU');

    $control->setAttribute( 'set_map_key', 'cnt_id' );
    $control->setAttribute( 'set_options', $countries );

    $form->loadSubmittedValues();

    return $form;
  }


  //--------------------------------------------------------------------------------------------------------------------
  /** @name ValidTests
  Tests for valid submitted values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  public function testValid2()
  {
    $_POST['cnt_id']['3'] = 'on';

    $form   = $this->setupForm2();
    $values = $form->getValues();

    // Test checkbox with index 3 has been checked.
    $this->assertTrue( $values['cnt_id']['3'] );

    // Test checkbox with index 1 has not been checked.
    $this->assertFalse( $values['cnt_id']['1'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Setups a form with a select form control. Difference between this function
   * and SetupForm1 are the cnt_id are integers.
   */
  private function setupForm2()
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'checkboxes', 'cnt_id' );

    $countries[] = array('cnt_id' => 0, 'cnt_name' => 'NL');
    $countries[] = array('cnt_id' => 1, 'cnt_name' => 'NL');
    $countries[] = array('cnt_id' => 2, 'cnt_name' => 'BE');
    $countries[] = array('cnt_id' => 3, 'cnt_name' => 'LU');

    $control->setAttribute( 'set_map_key', 'cnt_id' );
    $control->setAttribute( 'set_options', $countries );
    $control->setAttribute( 'set_map_checked', '2' );

    $form->loadSubmittedValues();

    return $form;
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
    $_POST['cnt_id']['99'] = 'on';

    $form    = $this->setupForm1();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayNotHasKey( 'cnt_id', $changed );
    $this->assertArrayNotHasKey( '99', $values['cnt_id'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Test a submitted value '0'.
   */
  public function test1Empty1()
  {
    $_POST['cnt_id']['0'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 3 has been checked.
    $this->assertTrue( $values['cnt_id']['0'] );

    // Test checkbox with index 1 has not been checked.
    $this->assertFalse( $values['cnt_id']['1'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetValues1()
  {
    $countries[] = array('cnt_id' => 0, 'cnt_name' => 'NL', 'checked'=> true );
    $countries[] = array('cnt_id' => 1, 'cnt_name' => 'BE', 'checked'=> true );
    $countries[] = array('cnt_id' => 2, 'cnt_name' => 'LU');
    $countries[] = array('cnt_id' => 3, 'cnt_name' => 'DE');
    $countries[] = array('cnt_id' => 4, 'cnt_name' => 'GB');


    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'checkboxes', 'cnt_id' );
    $control->setAttribute( 'set_options',      $countries );
    $control->setAttribute( 'set_map_id',      'cnt_id' );
    $control->setAttribute( 'set_map_key',     'cnt_id' );
    $control->setAttribute( 'set_map_label',   'cnt_name' );
    $control->setAttribute( 'set_map_checked', 'checked' );

    // Set the values of the form.
    $values['cnt_id'][0] = true;
    $values['cnt_id'][1] = false;
    $values['cnt_id'][2] = true;
    $values['cnt_id'][3] = true;
    $values['cnt_id'][4] = false;

    $form->setValues($values);

    // Generate document width form.
    $form = $form->Generate();

    $doc = new DOMDocument();
    $doc->loadXML($form);
    $xpath = new DOMXpath($doc);

    // Using names.
    $list = $xpath->query( "/form/fieldset/div/input[@name='cnt_id[0]' and @type='checkbox' and @id='0' and @checked='checked']" );
    $this->assertEquals( 1, $list->length );

    $list = $xpath->query( "/form/fieldset/div/input[@name='cnt_id[1]' and @type='checkbox' and @id='1' and not(@checked)]" );
    $this->assertEquals( 1, $list->length );

    $list = $xpath->query( "/form/fieldset/div/input[@name='cnt_id[2]' and @type='checkbox' and @id='2' and @checked='checked']" );
    $this->assertEquals( 1, $list->length );

    $list = $xpath->query( "/form/fieldset/div/input[@name='cnt_id[3]' and @type='checkbox' and @id='3' and @checked='checked']" );
    $this->assertEquals( 1, $list->length );

    $list = $xpath->query( "/form/fieldset/div/input[@name='cnt_id[4]' and @type='checkbox' and @id='4' and not(@checked)]" );
    $this->assertEquals( 1, $list->length );

  }

  //--------------------------------------------------------------------------------------------------------------------
}
