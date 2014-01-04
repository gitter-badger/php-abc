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
  echo "<title>Sample Checkbox.</title>\n";
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
  $legend->setLegendText( 'Checkbox' );

  $control = $fieldset->createFormControl( 'checkbox', 'test1' );
  $control->setPrefix('Sample checkbox 1');

  $control = $fieldset->createFormControl( 'checkbox', 'test2' );
  $control->setValue( true );
  $control->setPrefix('Sample checkbox 2');

  $fieldset = $form->createFieldSet( 'fieldset', '' );
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
