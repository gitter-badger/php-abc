<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class CheckboxControlTest Test class for testing SatBased\Html\Form\CheckboxControl class.
 */
class CheckboxControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test prefix and postfix labels.
   */
  public function testPrefixAndPostfix()
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('checkbox', 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $html = $form->Generate();

    $pos = strpos($html, 'Hello<input');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue1()
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $fieldset->createFormControl('checkbox', 'test1');

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value has not set.
    $this->assertFalse($values['test1']);
    // Value has not change.
    $this->assertArrayNotHasKey('test1', $changed);
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

    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $fieldset->createFormControl('checkbox', 'test2');


    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    $this->assertTrue($values['test2']);

    // Assert value has changed.
    $this->assertNotEmpty($changed['test2']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form checked.
   * In POST unchecked.
   */
  public function testSubmittedValue3()
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl('checkbox', 'test3');
    $control->setValue(true);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST checkbox unchecked.
    $this->assertFalse($values['test3']);

    // Value is change.
    $this->assertNotEmpty($changed['test3']);
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

    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl('checkbox', 'test4');
    $control->setValue(true);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    $this->assertTrue($values['test4']);

    // Value has not changed.
    $this->assertArrayNotHasKey('test4', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------
