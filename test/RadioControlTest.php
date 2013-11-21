<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class RadioControlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Test form for radio.
   */
  private function setForm1()
  {
    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', '1' );

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', '2' );

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', '3' );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm2()
  {
    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', 1 );
    $control->setAttribute( 'checked', true );

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', 2 );

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', 3 );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm3()
  {
    $form = new \SetBased\Html\Form();
    $fieldset = $form->createFieldSet();

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', '0' );


    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', '0.0' );
    $control->setAttribute( 'checked', true );

    $control = $fieldset->createFormControl( 'radio', 'name' );
    $control->setAttribute( 'value', ' ' );

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**@name ValidTests
  Test for valid submitted values.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** A white list values must be valid.
   */
  public function testValid1()
  {
    $_POST['name']= '2';

    $form = $this->setForm1();
    $values = $form->getValues();

    $this->assertEquals( '2', $values['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white list values must be valid.
   */
  public function testValid2()
  {
    $_POST['name']= '2';

    $form = $this->setForm2();
    $values = $form->getValues();

    $this->assertEquals( 2, $values['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid (even whens tring and integers are mixed).
   */
  public function testValid3()
  {
    $_POST['name']= '3';

    $form = $this->setForm2();
    $values = $form->getValues();

    $this->assertEquals( 3, $values['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A white listed value must be valid (even whens tring and integers are mixed).
   */
  public function testValid4()
  {
    $_POST['name']= '0.0';

    $form = $this->setForm3();
    $values = $form->getValues();

    $this->assertEquals( '0.0', $values['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}
  /** @name WhiteListTest
  Test for white list valus.
   */
  //@{
  //--------------------------------------------------------------------------------------------------------------------
  /** Only white list values must be value.
   */

  public function testWhiteList1()
  {
    $_POST['name'] = 'ten';

    $form = $this->setForm1();
    $values = $form->getValues();

    $this->assertArrayHasKey( 'name', $values );
    $this->assertNull( $values['name'] );
    $this->assertEmpty( $form->getChangedControls() );

  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Only white list values must be value.
   */

  public function testWhiteList2()
  {
    $_POST['name'] = '10';

    $form    = $this->setForm2();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    $this->assertArrayHasKey( 'name', $values );
    $this->assertNull( $values['name'] );

    $this->assertNotEmpty( $changed['name'] );
  }

  //--------------------------------------------------------------------------------------------------------------------
  //@}

  //--------------------------------------------------------------------------------------------------------------------

}
