<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
class DivControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('div', 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<div>');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '</div>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

