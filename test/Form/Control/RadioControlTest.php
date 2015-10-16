<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\RadioControl;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
class RadioControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('radio', 'name');

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
   * A white list values must be valid.
   */
  public function testValid1()
  {
    $_POST['name'] = '2';

    $form   = $this->setForm1();
    $values = $form->getValues();

    $this->assertEquals('2', $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white list values must be valid.
   */
  public function testValid2()
  {
    $_POST['name'] = '2';

    $form   = $this->setForm2();
    $values = $form->getValues();

    $this->assertEquals(2, $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid3()
  {
    $_POST['name'] = '3';

    $form   = $this->setForm2();
    $values = $form->getValues();

    $this->assertEquals(3, $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid4()
  {
    $_POST['name'] = '0.0';

    $form   = $this->setForm3();
    $values = $form->getValues();

    $this->assertEquals('0.0', $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white list values must be value.
   */
  public function testWhiteList1()
  {
    $_POST['name'] = 'ten';

    $form   = $this->setForm1();
    $values = $form->getValues();

    $this->assertArrayHasKey('name', $values);
    $this->assertNull($values['name']);
    $this->assertEmpty($form->getChangedControls());

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white list values must be value.
   */
  public function testWhiteList2()
  {
    $_POST['name'] = '10';

    $form    = $this->setForm2();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayHasKey('name', $values);
    $this->assertNull($values['name']);

    $this->assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form for radio.
   */
  private function setForm1()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    /** @var RadioControl $control */
    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue('1');

    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue('2');

    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue('3');

    $form->prepare();
    $form->generate();
    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm2()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    /** @var RadioControl $control */
    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue(1);
    $control->setValue(1);

    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue(2);

    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue(3);

    $form->prepare();
    $form->generate();
    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm3()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    /** @var RadioControl $control */
    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue('0');

    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue('0.0');
    $control->setValue('0.0');

    $control = $fieldset->createFormControl('radio', 'name');
    $control->setAttrValue(' ');

    $form->prepare();
    $form->generate();
    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------

}
