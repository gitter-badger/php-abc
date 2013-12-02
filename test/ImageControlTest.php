<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class ImageControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'image', 'name' );

    $control->setPrefix( 'Hello' );
    $control->setPostfix( 'World' );
    $html = $form->Generate();

    $pos = strpos( $html, 'Hello<input' );
    $this->assertNotEquals( false, $pos );

    $pos = strpos( $html, '/>World' );
    $this->assertNotEquals( false, $pos );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for absolute name for button.
   */
  public function testAbsoluteName()
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->CreateFieldSet();
    $complex1 = $fieldset->CreateFormControl( 'complex', 'level1' );
    $complex2 = $complex1->CreateFormControl( 'complex', 'level2' );

    $button = $complex2->CreateFormControl( 'image', 'absolute' );
    if (isset($theValue)) $button->setAttribute( 'value', $theValue );

    // Generate HTML.
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML( $html );
    $xpath = new DOMXpath($doc);

    // Names of buttons must be absolute.
    $list = $xpath->query( "/form/fieldset/input[@name='absolute' and @type='image']" );
    $this->assertEquals( 1, $list->length );

    // Buttons doesn't use full name.
    $list = $xpath->query( "/form/fieldset/input[@name='level1[level2][absolute]' and @type='image']" );
    $this->assertEquals( 0, $list->length );
  }

  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

