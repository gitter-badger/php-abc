<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
class RadiosControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid.
   */
  public function testValid1()
  {
    $_POST['cnt_id'] = '3';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    $this->assertEquals('3', $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid2()
  {
    $_POST['cnt_id'] = '3';

    $form   = $this->setupForm2();
    $values = $form->getValues();

    $this->assertEquals('3', $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteListed1()
  {
    // cnt_id is not a value that is in the white list of values (i.e. 1,2, and 3).
    $_POST['cnt_id'] = 99;

    $form   = $this->setupForm1();
    $values = $form->getValues();

    $this->assertArrayHasKey('cnt_id', $values);
    $this->assertNull($values['cnt_id']);
    $this->assertEmpty($form->getChangedControls());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setupForm1()
  {
    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];

    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl('radios', 'cnt_id');
    $control->setOptions($countries, 'cnt_id', 'cnt_name');

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control. Difference between this function
   * and SetupForm1 are the cnt_id are integers.
   */
  private function setupForm2()
  {
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'LU'];

    $form     = new Form();
    $fieldset = $form->createFieldSet();
    $control  = $fieldset->createFormControl('radios', 'cnt_id');
    $control->setValue('1');
    $control->setOptions($countries, 'cnt_id', 'cnt_name');

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}
