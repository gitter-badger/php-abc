<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class TextAreaControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testPruneWhitespaceNoChanged()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'textarea', 'test' );
    $control->setAttribute( 'value', 'Hello World!' );

    // Set cleaner for textarea field (default it off).
   // $control->setAttribute('set_clean', '\SetBased\Html\Clean::pruneWhitespace' );

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    $this->assertEquals( 'Hello World!', $values['test'] );

    // Value not change.
    $this->assertArrayNotHasKey( 'test', $changed );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
