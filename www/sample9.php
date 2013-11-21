<?php
//----------------------------------------------------------------------------------------------------------------------
require __DIR__.'/../vendor/autoload.php';

//----------------------------------------------------------------------------------------------------------------------
function Leader()
{
  echo "<?xml version='1.0' encoding='UTF-8'?>\n";
  echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
  echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' dir='ltr'>\n";
  echo "<head>\n";
  echo "<title>Sample Multi Checkbox</title>\n";
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

  $countries[] = array( 'cnt_id' =>   0,  'cnt_name' => 'NL' );
  $countries[] = array( 'cnt_id' =>   1,  'cnt_name' => 'BE' );
  $countries[] = array( 'cnt_id' => 0.1,  'cnt_name' => 'LU' );


  $form     = new \SetBased\Html\Form();
  $fieldset = $form->createFieldSet();
  $legend = $fieldset->createLegend();
  $legend->SetAttribute( 'set_inline', 'Other' );

  $control  = $fieldset->createFormControl( 'checkboxes', 'cnt_id' );
  $control->setAttribute( 'set_map_key', 'cnt_id' );
  $control->setAttribute( 'set_options', $countries );
  $control->setAttribute( 'set_map_label',  'cnt_name' );


  $fieldset = $form->CreateFieldSet( 'fieldset', 'some_name' );
  $control = $fieldset->CreateFormControl( 'submit', 'submit' );
  $control->SetAttribute( 'value', 'OK' );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function Demo()
{
  //$_POST['cnt_id']['99'] = 'on';

  $form = CreateForm();

  if ($form->IsSubmitted( 'submit' ))
  {
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

