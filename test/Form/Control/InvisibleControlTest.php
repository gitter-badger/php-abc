<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
class InvisibleControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testForm1()
  {
    $_POST['name'] = '2';

    $form    = $this->setupForm1();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Assert the value of "name" is still "1".
    $this->assertEquals('1', $values['name']);

    // Assert "name" has not be recorded as a changed value.
    $this->assertArrayNotHasKey('name', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('invisible', 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<input');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setupForm1()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('invisible', 'name');
    $control->setValue('1');

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


