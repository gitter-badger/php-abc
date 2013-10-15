<?php
//----------------------------------------------------------------------------------------------------------------------
require_once( __DIR__.'../lib/form.php' );

//----------------------------------------------------------------------------------------------------------------------
function Leader()
{
  echo "<?xml version='1.0' encoding='UTF-8'?>\n";
  echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
  echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' dir='lr'>\n";
  echo "<head>\n";
  echo "<title>Sample1</title>\n";
  echo "</head>\n";
  echo "<body>\n";
}

//----------------------------------------------------------------------------------------------------------------------
function Trailer()
{
  echo "</body>\n";
  echo "</html>\n";
}

//----------------------------------------------------------------------------------------------------------------------
function CreateForm()
{
  $form = new SET_HtmlForm();

  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'set_inline', 'Hidden & Invisable' );

  $control = $fieldset->CreateFormControl( 'hidden', 'example1' );
  $control->SetAttribute( 'value', 'old1' );

  $control = $fieldset->CreateFormControl( 'invisable', 'example2' );
  $control->SetAttribute( 'value', 'old2' );

  $control = $fieldset->CreateFormControl( 'constant', 'example3' );
  $control->SetAttribute( 'set_value', 'old3' );

  $fieldset = $form->CreateFieldSet( 'fieldset', 'somename' );
  $control = $fieldset->CreateFormControl( 'submit', 'submit' );
  $control->SetAttribute( 'value', 'OK' );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function Demo()
{
  $form = CreateForm();

  if ($form->IsSubmitted( 'submit' ))
  {
    $_POST['example1'] = 'new value1';
    $_POST['example2'] = 'new value2';
    $_POST['example3'] = 'new value3';
    $form->LoadSubmittedValues();
    $valid = $form->Validate();
    if (!$valid && false)
    {
      echo $form->Generate();
    }
    else
    {
      echo "Html:";
      echo "<pre>";
      echo htmlentities( $form->Generate() );
      echo "</pre>";

      echo "Post:";
      echo "<pre>";
      print_r( $_POST );
      echo "</pre>";

      echo "Values:";
      echo "<pre>";
      print_r( $form->GetValues() );
      echo "</pre>";

      echo "Changed:";
      echo "<pre>";
      print_r( $form->GetChangedControls() );
      echo "</pre>";

      echo "Invalid:";
      echo "<pre>";
      print_r( $form->GetInvalidControls() );
      echo "</pre>";

      echo $form->Generate();
    }
  }
  else
  {
    echo $form->Generate();
  }
}

//----------------------------------------------------------------------------------------------------------------------
Leader();
Demo();
Trailer();
