<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class MandatoryValidatorTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory text, password, hidden or textarea form control with value @c null, @c false, or @c '', is invalid.
   */
  public function testInvalidEmpty()
  {
    $types  = array('text', 'password', 'hidden', 'textarea', 'checkbox');
    $values = array(null, false, '');

    foreach ($types as $i => $type)
    {
      foreach ($values as $j => $value)
      {

        $_POST['input'] = $value;
        $form           = $this->setupForm1( $type );

        $this->assertFalse( $form->validate(),
                            sprintf( "type: '%s', value: '%s'.", $type, var_export( $value, true ) ) );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory text, password, or textarea form control with whitespace is invalid.
   */
  public function testInvalidWhitespace()
  {
    $type   = array('text', 'password', 'textarea');
    $values = array(' ', '  ', " \n  ");

    foreach ($type as $i => $type)
    {
      foreach ($values as $j => $value)
      {

        $_POST['input'] = $value;
        $form           = $this->setupForm1( $type );

        $this->assertFalse( $form->validate(),
                            sprintf( "type: '%s', value: '%s'.", $type, var_export( $value, true ) ) );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory checked checked box is valid.
   */
  public function testValidCheckedCheckbox()
  {
    $_POST['box'] = 'on';
    $form         = $this->setupForm2();

    $this->assertTrue( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory non-empty text, password, hidden, or textarea form control is valid.
   */
  public function testValidNoneEmptyText()
  {
    $types = array('text', 'password', 'hidden', 'textarea');

    foreach ($types as $i => $type)
    {
      $_POST['input'] = 'Set Based IT Consultancy';
      $form           = $this->setupForm1( $type );

      $this->assertTrue( $form->validate(), sprintf( "type: '%s'.", $type ) );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes

  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory unchecked checkd box is invalid.
   */
  public function testinvalidUncheckedCheckbox()
  {
    $_POST = array();
    $form  = $this->setupForm2();

    $this->assertFalse( $form->validate() );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a single form control of type $theType.
   */
  private function setupForm1( $theType )
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->createFieldSet( 'fieldset' );

    $control = $fieldset->createFormControl( $theType, 'input' );
    $control->addValidator( new \SetBased\Html\Form\MandatoryValidator() );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a checkbox form control.
   */
  private function setupForm2()
  {
    $form = new \SetBased\Html\Form();

    $fieldset = $form->createFieldSet( 'fieldset' );

    $control = $fieldset->createFormControl( 'checkbox', 'box' );
    $control->addValidator( new \SetBased\Html\Form\MandatoryValidator() );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes
  //--------------------------------------------------------------------------------------------------------------------

}

//----------------------------------------------------------------------------------------------------------------------

