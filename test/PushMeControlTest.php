<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class PushMeControlTest
 */
abstract class PushMeControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for absolute name for button.
   */
  public function testAbsoluteName()
  {
    // Create form control.
    $form = $this->setupForm1( 'test', false );

    // Generate HTML.
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML( $html );
    $xpath = new DOMXpath($doc);

    // Names of buttons must be absolute.
    $list = $xpath->query( "/form/fieldset/input[@name='absolute' and @type='".$this->getControlType()."']" );
    $this->assertEquals( 1, $list->length );

    // Buttons doesn't use full name.
    $list = $xpath->query( "/form/fieldset/input[@name='level1[level2][absolute]' and @type='".$this->getControlType()."']" );
    $this->assertEquals( 0, $list->length );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theValue string The type of the button to be tested.
   *
   * @return Form
   */
  private function setupForm1( $theValue )
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->CreateFieldSet();
    $complex1 = $fieldset->CreateFormControl( 'complex', 'level1' );
    $complex2 = $complex1->CreateFormControl( 'complex', 'level2' );

    $button = $complex2->CreateFormControl( $this->getControlType(), 'absolute' );
    if(isset($theValue)) $button->setAttribute( 'value', $theValue );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return type of form control.
   * @return string
   */
  abstract protected function getControlType();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method SetValue() has no effect for buttons.
   */
  public function testSetValues()
  {
    // Create form.
    $form = new \SetBased\Html\Form();
    $fieldset = $form->CreateFieldSet();
    $button = $fieldset->CreateFormControl( $this->getControlType(), 'button' );
    $button->setAttribute( 'value', "Do not push" );

    // Set the values for button.
    $values['button'] = 'Push';
    $form->setValues( $values );

    // Generate HTML.
    $html = $form->generate();

    $doc = new DOMDocument();
    $doc->loadXML( $html );
    $xpath = new DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query( "/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']" );
    $this->assertEquals( 1, $list->length );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
