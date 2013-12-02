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
  echo "<title>Sample Buttons</title>\n";
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

  $complex1 = $form->createFieldSet();
  $legend = $complex1->createLegend();
  $legend->setLegendText( 'Buttons' );

  $complex2 = $complex1->createFormControl( 'complex' , 'level1' );
  $fieldset = $complex2->createFormControl( 'complex' , 'level2' );


  $control = $fieldset->createFormControl( 'text' , 'text' );
  $control->setValue( 'Sample text' );

  $control = $fieldset->createFormControl( 'button' , 'button' );
  $control->setValue( 'Sample Button' );


  $control = $fieldset->createFormControl( 'reset' , 'reset' );
  $control->setValue( 'Reset Button' );

  $control = $fieldset->createFormControl( 'submit', 'submit' );
  $control->setValue( 'Submit Button' );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function demo()
{
  $form = createForm();

  if ($form->isSubmitted( 'submit' ))
  {
    $form->LoadSubmittedValues();

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
