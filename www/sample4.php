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
  echo "<title>Sample Radio</title>\n";
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
  $form = new \SetBased\Html\Form();

  $fieldset = $form->createFieldSet();
  $legend = $fieldset->createLegend();
  $legend->setLegendText( 'Other' );

  $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
  $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
  $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );
  $countries[] = array( 'cnt_id' => '4', 'cnt_name' => 'UA' );

  $control = $fieldset->createFormControl( 'radios', 'cnt_id' );
  $control->setOptions( $countries, 'cnt_id', 'cnt_name' );
  $control->setPostfix( '<br/>' );

  $fieldset = $form->createFieldSet( 'fieldset', 'somename' );
  $control = $fieldset->createFormControl( 'submit', 'submit' );
  $control->setValue( 'OK' );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function demo()
{
  $_POST['cnt_id']['99'] = 'on';

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
      echo "Html:";
      echo "<pre>";
      echo htmlentities( $form->generate() );
      echo "</pre>";

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
