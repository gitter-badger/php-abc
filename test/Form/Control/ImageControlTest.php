<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
class ImageControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for absolute name for image.
   */
  public function testAbsoluteName()
  {
    $form = new Form();

    $fieldset = $form->CreateFieldSet();
    $complex1 = $fieldset->CreateFormControl('complex', 'level1');
    $complex2 = $complex1->CreateFormControl('complex', 'level2');

    $button = $complex2->CreateFormControl('image', 'absolute');
    if (isset($theValue)) $button->setValue($theValue);

    // Generate HTML.
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML($html);
    $xpath = new DOMXpath($doc);


    // An image control has an absolute name.
    $list = $xpath->query("/form/fieldset/input[@name='absolute' and @type='image']");
    $this->assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl('image', 'name');

    $control->setPrefix('Hello');
    $control->setPostfix('World');
    $html = $form->Generate();

    $pos = strpos($html, 'Hello<input');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

