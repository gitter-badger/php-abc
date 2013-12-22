<?php
//----------------------------------------------------------------------------------------------------------------------
class TextControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testPruneWhitespaceNoChanged()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'text', 'test' );
    $control->setValue( 'Hello World!' );

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    $this->assertEquals( 'Hello World!', $values['test'] );

    // Value not change.
    $this->assertArrayNotHasKey( 'test', $changed );

  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getInputType()
  {
    return 'text';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
