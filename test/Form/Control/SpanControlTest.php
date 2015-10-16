<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
class SpanControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('span', 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<span>');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '</span>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

