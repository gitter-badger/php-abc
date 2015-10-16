<?php
//----------------------------------------------------------------------------------------------------------------------
require_once(__DIR__.'/SimpleControlTest.php');

use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
class HiddenControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test change value.
   */
  public function testValue()
  {
    $_POST['test'] = 'New value';

    $form     = new RawForm();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl('hidden', 'test');
    $control->setValue('Old value');

    $form->loadSubmittedValues();

    $changed = $form->getChangedControls();

    // Value is change.
    $this->assertNotEmpty($changed['test']);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testWhitespace()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new RawForm();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl('hidden', 'test');
    $control->setValue('Hello World!');

    // Set cleaner for hidden field (default it off).
    $control->setCleaner(PruneWhitespaceCleaner::get());

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    $this->assertEquals('Hello World!', $values['test']);

    // Value not change.
    $this->assertArrayNotHasKey('test', $changed);

  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getInputType()
  {
    return 'hidden';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
