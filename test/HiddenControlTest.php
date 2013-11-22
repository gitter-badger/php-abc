<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( 'test/SimpleControlTest.php' );

//----------------------------------------------------------------------------------------------------------------------
class HiddenControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  protected function getInputType()
  {
    return 'hidden';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testWhitespace()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control = $fieldset->createFormControl('hidden', 'test');
    $control->setAttribute('value', 'Hello World!');

    // Set cleaner for hidden field (default it off).
  //  $control->setAttribute('set_clean', '\SetBased\Html\Form\Cleaner\PruneWhitespaceCleaner::clean' );

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    $this->assertEquals( 'Hello World!', $values['test'] );

    // Value not change.
    $this->assertArrayNotHasKey( 'test', $changed );

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test change value.
   */
  public function testValue()
  {
    $_POST['test'] = 'New value';

    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control = $fieldset->createFormControl('hidden', 'test');
    $control->setAttribute('value', 'Old value');

    $form->loadSubmittedValues();

    $changed = $form->getChangedControls();

    // Value is change.
    $this->assertNotEmpty( $changed['test'] );

  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
