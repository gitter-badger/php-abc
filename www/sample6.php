<?php
//----------------------------------------------------------------------------------------------------------------------
define( 'NAHOUW_HOME', dirname(__FILE__).'/..' );

//----------------------------------------------------------------------------------------------------------------------
require_once( NAHOUW_HOME.'/lib/form.php' );

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
  $form = new H2O_HtmlForm();

  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'h2o_inline', 'Gender' );

  $control = $fieldset->CreateFormControl( 'radio', 'gender' );
  $control->SetAttribute( 'h2o_prefix', 'male' );
 // $control->SetAttribute( 'checked', true );
  $control->SetAttribute( 'value', '0.0' );

  $control = $fieldset->CreateFormControl( 'radio' , 'gender' );
  $control->SetAttribute( 'h2o_prefix', 'female' );
  //$control->SetAttribute( 'checked', true );
  $control->SetAttribute( 'value', '0' );

  $control = $fieldset->CreateFormControl( 'radio' , 'gender' );
  $control->SetAttribute( 'h2o_prefix', 'unknown' );
  //$control->SetAttribute( 'checked', true );
  $control->SetAttribute( 'value', 'unknown' );

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
    //$_POST[gender] = "It";
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

    echo "Html:";
    echo "<pre>";
    echo htmlentities( $form->Generate() );
    echo "</pre>";  }
}

//----------------------------------------------------------------------------------------------------------------------
Leader();
Demo();
Trailer();
