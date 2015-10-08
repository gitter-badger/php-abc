<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
class SpanControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('span', 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $html = $form->Generate();

    $pos = strpos($html, 'Hello<span>');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '</span>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

