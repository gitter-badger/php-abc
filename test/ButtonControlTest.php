<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ButtonControlTest
 */
class ButtonControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var array The supported button types.
   */
  private $myButtonTypes = array('button', 'submit', 'reset');

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for absolute name for button.
   */
  public function testAbsoluteName()
  {
    foreach ($this->myButtonTypes as $buttonType)
    {
      // Create form control.
      $form = $this->setupForm1( $buttonType );

      // Generate HTML.
      $html = $form->generate();

      $doc = new DOMDocument();
      $doc->loadXML( $html );
      $xpath = new DOMXpath($doc);

      // Names of buttons must be absolute.
      $list = $xpath->query( "/form/fieldset/input[@name='absolute' and @type='".$buttonType."']" );
      $this->assertEquals( 1, $list->length );

      // Buttons doesn't use full name.
      $list = $xpath->query( "/form/fieldset/input[@name='level1[level2][absolute]' and @type='".$buttonType."']" );
      $this->assertEquals( 0, $list->length );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theButtonType string The type of the button to be tested.
   *
   * @return Form
   */
  private function setupForm1( $theButtonType )
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->CreateFieldSet();
    $complex1 = $fieldset->CreateFormControl( 'complex', 'level1' );
    $complex2 = $complex1->CreateFormControl( 'complex', 'level2' );

    $button = $complex2->CreateFormControl( $theButtonType, 'absolute' );
    $button->setAttribute( 'value', 'test' );

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
