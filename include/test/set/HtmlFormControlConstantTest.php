<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'set/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlConstantTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  private function SetupForm1()
  {
    $form = new SET_HtmlForm();
    $fieldset= $form->CreateFieldSet();

    $control = $fieldset->CreateFormControl( 'constant' , 'name' );
    $control->SetAttribute( 'set_value', '1' );

    $form->LoadSubmittedValues();

    return $form;
  }

  //-------------------------------------------------------------------------------------------------------------------
  public function testForm1()
  {
    $_POST['name'] = '2';

    $form    = $this->SetupForm1();
    $values  = $form->GetValues();
    $changed = $form->GetChangedControls();

    // Assert the value of "name" is still "1".
    $this->assertEquals( '1', $values['name'] );

    // Assert "name" has not be recored as a changed value.
    $this->assertArrayNotHasKey( 'name', $changed );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

