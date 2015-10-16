<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\MandatoryValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ButtonControlTest
 */
class ComplexControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var ComplexControl
   */
  private $myOriginComplexControl;

  /**
   * @var SimpleControl
   */
  private $myOriginControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test find FormControl by name.
   */
  public function testFindFormControlByName()
  {
    $form = $this->setForm1();

    // Find form control by name. Must return object.
    $control = $form->findFormControlByName('street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    // Find form control by name what does not exist. Must return null.
    $control = $form->findFormControlByName('not_exists');
    $this->assertEquals(null, $control);

    $control = $form->findFormControlByName('/no_path/not_exists');
    $this->assertEquals(null, $control);

    $control = $form->findFormControlByName('/vacation/not_exists');
    $this->assertEquals(null, $control);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test find FormControl by path.
   */
  public function testFindFormControlByPath()
  {
    $form = $this->setForm1();

    // Find form control by path. Must return object.
    $control = $form->findFormControlByPath('/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->findFormControlByPath('/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->findFormControlByPath('/post/zip-code');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->findFormControlByPath('/vacation/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->findFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->findFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);


    // Find form control by path what does not exist. Must return null.
    $control = $form->findFormControlByPath('/not_exists');
    $this->assertEquals(null, $control);

    $control = $form->findFormControlByPath('/no_path/not_exists');
    $this->assertEquals(null, $control);

    $control = $form->findFormControlByPath('/vacation/not_exists');
    $this->assertEquals(null, $control);

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by name.
   */
  public function testGetFormControlByName()
  {
    $form = $this->setForm1();

    // Get form control by name. Must return object.
    $control = $form->getFormControlByName('vacation');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $control->getFormControlByName('city2');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);
    $this->assertEquals('city2', $control->getLocalName());
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by path.
   */
  public function testGetFormControlByPath()
  {
    $form = $this->setForm1();

    // Get form control by path. Must return object.
    $control = $form->getFormControlByPath('/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->getFormControlByPath('/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->getFormControlByPath('/vacation/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->getFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);

    $control = $form->getFormControlByPath('/vacation/post/street');
    $this->assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $control);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName1()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName2()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('/no_path/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByName3()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath1()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath2()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('street');
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath3()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/no_path/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException Exception
   */
  public function testGetNotExistsFormControlByPath4()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   */
  public function testSubmitValues()
  {
    $_POST['field_1']                                  = 'value';
    $_POST['field_3']                                  = 'value';
    $_POST['complex_name']['field_2']                  = 'value';
    $_POST['complex_name']['complex_name2']['field_4'] = 'value';

    $form = $this->setForm2();
    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayHasKey('field_1', $values);
    $this->assertArrayHasKey('field_2', $values['complex_name']);

    $this->assertArrayHasKey('field_3', $values['complex_name']);
    $this->assertArrayHasKey('field_4', $values['complex_name']['complex_name2']);

    $this->assertNotEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with alphanumeric name of the control in the complex control.
   */
  public function testValid4()
  {
    $names = ['test01', 10, 0, '0.0'];

    foreach ($names as $name)
    {
      // Create form with control inside of complex control.
      $form = $this->setForm3($name);

      // Firs find complex control by name.
      $complex_control = $form->findFormControlByName($name);

      // Test for complex control.
      $this->assertNotEmpty($complex_control);
      $this->assertEquals($this->myOriginComplexControl, $complex_control);
      $this->assertEquals($name, $complex_control->getLocalName());

      // Find control by name.
      $control = $complex_control->findFormControlByName($name);

      // Test for control.
      $this->assertNotEmpty($control);
      $this->assertEquals($this->myOriginControl, $control);
      $this->assertEquals($name, $control->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Each form controls in form must validate and add to invalid controls if it not valid.
   */
  public function testValidate()
  {
    $form = new RawForm();

    $field_set = $form->createFieldSet('fieldset');

    // Create mandatory control.
    $control = $field_set->createFormControl('checkbox', 'input_1');
    $control->addValidator(new MandatoryValidator());

    // Create optional control.
    $field_set->createFormControl('checkbox', 'input_2');

    // Create mandatory control.
    $control = $field_set->createFormControl('checkbox', 'input_3');
    $control->addValidator(new MandatoryValidator());

    // Simulate a post without any values.
    $form->loadSubmittedValues();
    $form->validate();
    $invalid = $form->getInvalidControls();

    // We expect 2 invalid controls.
    $this->assertCount(2, $invalid);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteList()
  {
    $_POST['unknown_field']                    = 'value';
    $_POST['unknown_complex']['unknown_field'] = 'value';

    $form = $this->setForm2();
    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayNotHasKey('unknown_field', $values);
    $this->assertArrayNotHasKey('unknown_complex', $values);

    $this->assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm1()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();
    $complex  = $fieldset->createFormControl('complex', '');
    $complex->createFormControl('text', 'street');
    $complex->createFormControl('text', 'city');

    $complex = $fieldset->createFormControl('complex', 'post');
    $complex->createFormControl('text', 'street');
    $complex->createFormControl('text', 'city');

    $complex = $fieldset->createFormControl('complex', 'post');
    $complex->createFormControl('text', 'zip-code');
    $complex->createFormControl('text', 'state');

    $complex = $fieldset->createFormControl('complex', 'post');
    $complex->createFormControl('text', 'zip-code');
    $complex->createFormControl('text', 'state');

    $fieldset = $form->createFieldSet('fieldset', 'vacation');
    $complex  = $fieldset->createFormControl('complex', '');
    $complex->createFormControl('text', 'street');
    $complex->createFormControl('text', 'city');

    $complex = $fieldset->createFormControl('complex', 'post');
    $complex->createFormControl('text', 'street');
    $complex->createFormControl('text', 'city');

    $complex2 = $complex->createFormControl('complex', '');
    $complex2->createFormControl('text', 'street2');
    $complex2->createFormControl('text', 'city2');

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm2()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $complex = $fieldset->createFormControl('complex', '');
    $complex->createFormControl('text', 'field_1');

    $complex = $fieldset->createFormControl('complex', 'complex_name');
    $complex->createFormControl('text', 'field_2');


    $complex2 = $complex->createFormControl('complex', '');
    $complex2->createFormControl('text', 'field_3');

    $complex2 = $complex->createFormControl('complex', 'complex_name2');
    $complex2->createFormControl('text', 'field_4');

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form with specific name of control.
   *
   * @param string $theName The name of the form control.
   *
   * @return RawForm
   */
  private function setForm3($theName)
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $complex = $fieldset->createFormControl('complex', $theName);
    $control = $complex->createFormControl('text', $theName);

    $this->myOriginComplexControl = $complex;
    $this->myOriginControl        = $control;

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
