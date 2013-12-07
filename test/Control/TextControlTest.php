<?php
//----------------------------------------------------------------------------------------------------------------------
class TextControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testDateFormattingAndCleaning()
  {
    $_POST['birthday'] = '10.04.1966';

    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'text', 'birthday' );
    $control->setValue( '1966-04-10' );
    $control->setCleaner( new \SetBased\Html\Form\Cleaner\DateCleaner('d-m-Y', '-', '/-. ') );
    $control->setFormatter( new \SetBased\Html\Form\Formatter\DateFormatter('d-m-Y') );

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After formatting and clean the date must be in ISO 8601 format.
    $this->assertEquals( '1966-04-10', $values['birthday'] );

    // Effectively the date is not changed.
    $this->assertArrayNotHasKey( 'birthday', $changed );

  }

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

    // Effectively the value is not changed.
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
