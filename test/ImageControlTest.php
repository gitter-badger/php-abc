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

}

//----------------------------------------------------------------------------------------------------------------------

