<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class PushMeControlTest
 */
abstract class PushMeControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl($this->getControlType(), 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $form->prepare();
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<input');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method SetValue() has no effect for buttons.
   */
  public function testSetValues()
  {
    // Create form.
    $form     = new RawForm();
    $fieldset = $form->CreateFieldSet();
    $button   = $fieldset->createFormControl($this->getControlType(), 'button');
    $button->setValue("Do not push");

    // Set the values for button.
    $values['button'] = 'Push';
    $form->setValues($values);

    // Generate HTML.
    $form->prepare();
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML($html);
    $xpath = new DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']");
    $this->assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return type of form control.
   *
   * @return string
   */
  abstract protected function getControlType();

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
