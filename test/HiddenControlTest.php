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
    $control->setAttribute('set_value', 'Hello World!');
    $control->setAttribute('set_clean', '\SetBased\Html\Clean::pruneWhitespace' );


    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals( 'Hello World!', $values['test'] );

    $this->assertTrue( $changed['test'] );

  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
