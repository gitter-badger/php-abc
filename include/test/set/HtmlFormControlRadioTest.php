<?php
//----------------------------------------------------------------------------------------------------------------------
require_once('set/form.php');

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormContorlRadioTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Test form for radio.
   */
  private function SetForm1()
  {
    $form = new SET_HtmlForm;
    $fieldset = $form->CreateFieldSet();

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', '1' );

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', '2' );

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', '3' );

    $form->LoadSubmittedValues();

    return $form;
   }

  //--------------------------------------------------------------------------------------------------------------------
  private function SetForm2()
  {
    $form = new SET_HtmlForm;
    $fieldset = $form->CreateFieldSet();

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', 1 );
    $control->SetAttribute( 'checked', true );

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', 2 );

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', 3 );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function SetForm3()
  {
    $form = new SET_HtmlForm;
    $fieldset = $form->CreateFieldSet();

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', '0' );


    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', '0.0' );
    $control->SetAttribute( 'checked', true );

    $control = $fieldset->CreateFormControl( 'radio', 'name' );
    $control->SetAttribute( 'value', ' ' );

    $form->LoadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**@name ValidTests
     Test for valid submitted values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A white list values must be valid.
   */
  public function testValid1()
  {
    $_POST['name']= '2';

    $form = $this->SetForm1();
    $values = $form->GetValues();

    $this->assertEquals( '2', $values['name'] );
   }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white list values must be valid.
   */
  public function testValid2()
  {
    $_POST['name']= '2';

    $form = $this->SetForm2();
    $values = $form->GetValues();

    $this->assertEquals( 2, $values['name'] );
   }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid (even whens tring and integers are mixed).
   */
  public function testValid3()
  {
    $_POST['name']= '3';

    $form = $this->SetForm2();
    $values = $form->GetValues();

    $this->assertEquals( 3, $values['name'] );
   }

     //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid (even whens tring and integers are mixed).
   */
  public function testValid4()
  {
    $_POST['name']= '0.0';

    $form = $this->SetForm3();
    $values = $form->GetValues();

    $this->assertEquals( '0.0', $values['name'] );
   }

  //--------------------------------------------------------------------------------------------------------------------
  //@}
  /** @name WhiteListTest
      Test for white list valus.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** Only white list values must be value.
   */

  public function testWhiteList1()
  {
    $_POST['name'] = 'ten';

    $form = $this->SetForm1();
    $values = $form->GetValues();

    $this->assertArrayHasKey( 'name', $values );
    $this->assertNull( $values['name'] );
    $this->assertEmpty( $form->GetChangedControls() );

   }

  //--------------------------------------------------------------------------------------------------------------------
  /** Only white list values must be value.
   */

  public function testWhiteList2()
  {
    $_POST['name'] = '10';

    $form    = $this->SetForm2();
    $values  = $form->GetValues();
    $changed = $form->GetChangedControls();

    $this->assertArrayHasKey( 'name', $values );
    $this->assertNull( $values['name'] );
    $this->assertTrue( $changed['name'] );
   }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  //--------------------------------------------------------------------------------------------------------------------

}
