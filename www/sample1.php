<?php
//----------------------------------------------------------------------------------------------------------------------
require __DIR__.'/../vendor/autoload.php';

//----------------------------------------------------------------------------------------------------------------------
function leader()
{
  echo "<?xml version='1.0' encoding='UTF-8'?>\n";
  echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
  echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' dir='ltr'>\n";
  echo "<head>\n";
  echo "<title>Sample Form</title>\n";
  echo "</head>\n";
  echo "<body>\n";
}

//----------------------------------------------------------------------------------------------------------------------
function trailer()
{
  echo "</body>\n";
  echo "</html>\n";
}

//----------------------------------------------------------------------------------------------------------------------
function createForm()
{
  $form = new \SetBased\Html\Form('hi1');

  $fieldset = $form->createFieldSet();
  $legend = $fieldset->createLegend();
  $legend->setLegendText( 'Name' );

  $control = $fieldset->createFormControl( 'text', 'first_name' );
  $control->setPostfix( '<br/>' );
  $control->setLabelText( 'first  name' );
  $control->setLabelPosition( 'pre' );

  $control = $fieldset->createFormControl( 'text', 'last_name' );
  $control->setPostfix( '<br/>' );
  $control->setLabelText( 'last name' );
  $control->setLabelPosition( 'pre' );

  $fieldset = $form->createFieldSet();
  $legend = $fieldset->createLegend();
  $legend->setLegendText( 'URI' );

  $control = $fieldset->createFormControl( 'text', 'email' );
  $control->setPostfix( '<br/>' );
  $control->setLabelText( 'email' );
  $control->setLabelPosition( 'pre' );
  $control->addValidator( new \SetBased\Html\Form\Validator\EmailValidator() );

  $control = $fieldset->createFormControl( 'text', 'url' );
  $control->setPostfix( '<br/>' );
  $control->setLabelText( 'url' );
  $control->setLabelPosition( 'pre' );
  $control->addValidator( new \SetBased\Html\Form\Validator\HttpValidator() );

  $fieldset = $form->createFieldSet();
  $legend = $fieldset->createLegend();
  $legend->setLegendText( 'Other' );

  $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
  $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
  $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );

  $control = $fieldset->createFormControl( 'select', 'cnt_id' );
  $control->setValue( '1' );
  $control->setOptions( $countries, 'cnt_id', 'cnt_name' );
  $control->setEmptyOption( true );

  $control->setPostfix( '<br/>' );
  $control->setLabelText( 'Country' );
  $control->setLabelPosition( 'pre' );

  $fieldset = $form->createFieldSet( 'fieldset', 'somename' );
  $control = $fieldset->createFormControl( 'submit', 'submit' );
  $control->setValue( 'OK' );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function demo()
{
  $form = createForm();

  if ($form->isSubmitted( 'submit' ))
  {
    $form->loadSubmittedValues();
    $valid = $form->validate();
    if (!$valid && false)
    {
      echo $form->generate();
    }
    else
    {
      echo "Post:";
      echo "<pre>";
      print_r( $_POST );
      echo "</pre>";

      echo "Values:";
      echo "<pre>";
      print_r( $form->getValues() );
      echo "</pre>";

      echo "Changed:";
      echo "<pre>";
      print_r( $form->getChangedControls() );
      echo "</pre>";

      echo "Invalid:";
      echo "<pre>";
      print_r( $form->getInvalidControls() );
      echo "</pre>";

      echo $form->generate();
    }
  }
  else
  {
    echo $form->generate();
  }
}

//----------------------------------------------------------------------------------------------------------------------
leader();
demo();
trailer();

