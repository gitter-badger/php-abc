<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class CheckboxControlTest Test class for testing SatBased\Html\Form\CheckboxControl class.
 */
class CheckboxControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue1()
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $fieldset->createFormControl( 'checkbox', 'test1' );

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value has not set.
    $this->assertFalse( $values['test1'] );
    // Value has not change.
    $this->assertArrayNotHasKey( 'test1', $changed );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue2()
  {
    $_POST['test2'] = 'on';

    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $fieldset->createFormControl( 'checkbox', 'test2' );


    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    $this->assertTrue( $values['test2'] );

    // Value is change.
    $this->assertTrue( $changed['test2'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form checked.
   * In POST unchecked.
   */
  public function testSubmittedValue3()
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control = $fieldset->createFormControl( 'checkbox', 'test3' );
    $control->setAttribute('checked', true );

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST checkbox unchecked.
    $this->assertFalse( $values['test3'] );

    // Value is change.
    $this->assertTrue( $changed['test3'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue4()
  {
    $_POST['test4'] = 'on';

    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();
    $control = $fieldset->createFormControl( 'checkbox', 'test4' );
    $control->setAttribute('checked', true );

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    $this->assertTrue( $values['test4'] );

    // Value has not changed.
    $this->assertArrayNotHasKey( 'test4', $changed );
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------
