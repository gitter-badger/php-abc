<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Form;
use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;

//----------------------------------------------------------------------------------------------------------------------
class TextAreaControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'checkbox', 'name' );

    $control->setPrefix( 'Hello' );
    $control->setPostfix( 'World' );
    $html = $form->Generate();

    $pos = strpos( $html, 'Hello<input' );
    $this->assertNotEquals( false, $pos );

    $pos = strpos( $html, '/>World' );
    $this->assertNotEquals( false, $pos );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testPruneWhitespaceNoChanged()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'textarea', 'test' );
    $control->setValue( 'Hello World!' );

    // Set cleaner for textarea field (default it off).
    $control->setCleaner( PruneWhitespaceCleaner::get() );

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
   * Test submit value.
   */
  public function testSubmittedValue()
  {
    $_POST['test'] = 'Hello World!';

    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl( 'textarea', 'test' );
    $control->setValue( 'Hi World!' );

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( 'Hello World!', $values['test'] );

    // Value is change.
    $this->assertNotEmpty( $changed['test'] );
  }
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
