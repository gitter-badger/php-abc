<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * @brief Abstract super class for test for TextControl, HiddenControl, and PasswordControl.
 */
abstract class SimpleControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0'.
   */
  public function test1Empty1()
  {
    $name          = 0;
    $_POST['name'] = $name;

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals($name, $values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0.0'.
   */
  public function test1Empty2()
  {
    $name          = '0.0';
    $_POST['name'] = $name;

    $form    = $this->setupForm1('');
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals($name, $values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl($this->getInputType(), 'name');
    $control->setValue('1');

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
  /**
   * Test a submitted value.
   */
  public function testValid101()
  {
    $name          = 'Set Based IT Consultancy';
    $_POST['name'] = $name;

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEquals($name, $values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted empty value.
   */
  public function testValid102()
  {
    $name          = 'Set Based IT Consultancy';
    $_POST['name'] = '';

    $form    = $this->setupForm1($name);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertEmpty($values['name']);
    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function getInputType();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control.
   *
   * @param string $theValue The value of the form control
   *
   * @return RawForm
   */
  private function setupForm1($theValue)
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl($this->getInputType(), 'name');
    if (isset($theValue)) $control->setValue($theValue);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

