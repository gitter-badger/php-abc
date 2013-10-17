<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'lib/form.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlConstantTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  private function SetupForm1()
  {
    $form = new SET_HtmlForm();
    $fieldset= $form->createFieldSet();

    $control = $fieldset->createFormControl( 'constant' , 'name' );
    $control->setAttribute( 'set_value', '1' );

    $form->loadSubmittedValues();

    return $form;
  }

  //-------------------------------------------------------------------------------------------------------------------
  public function testForm1()
  {
    $_POST['name'] = '2';

    $form    = $this->setupForm1();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Assert the value of "name" is still "1".
    $this->assertEquals( '1', $values['name'] );

    // Assert "name" has not be recored as a changed value.
    $this->assertArrayNotHasKey( 'name', $changed );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

