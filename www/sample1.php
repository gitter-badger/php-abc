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
  echo "<title>Sample Form</title>\n";
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
  $form = new \SetBased\Html\Form();


  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'set_inline', 'Name' );

  $control = $fieldset->CreateFormControl( 'text', 'first_name' );
  $control->SetAttribute( 'set_postfix', '<br/>' );
  $control->SetLabelAttribute( 'set_position', 'prefix' );
  $control->SetLabelAttribute( 'set_label', 'first  name' );

  $control = $fieldset->CreateFormControl( 'text', 'last_name' );
  $control->SetAttribute( 'set_postfix', '<br/>' );
  $control->SetLabelAttribute( 'set_position', 'prefix' );
  $control->SetLabelAttribute( 'set_label', 'last  name' );


  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'set_inline', 'URI' );

  $control = $fieldset->CreateFormControl( 'text', 'email' );
  $control->SetAttribute( 'set_postfix', '<br/>' );
  $control->SetLabelAttribute( 'set_position', 'prefix' );
  $control->SetLabelAttribute( 'set_label', 'email' );
  $control->AddValidator( new \SetBased\Html\Form\EmailValidator() );

  $control = $fieldset->CreateFormControl( 'text', 'url' );
  $control->SetAttribute( 'set_postfix', '<br/>' );
  $control->SetLabelAttribute( 'set_position', 'prefix' );
  $control->SetLabelAttribute( 'set_label', 'url' );
  $control->AddValidator( new \SetBased\Html\Form\HttpValidator() );

  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'set_inline', 'Other' );

  $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
  $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
  $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );

  $control = $fieldset->CreateFormControl( 'select', 'cnt_id' );

  $control->SetAttribute( 'set_map_key',        'cnt_id' );
  $control->SetAttribute( 'set_map_label',      'cnt_name' );
  $control->SetAttribute( 'set_value',          '1' );
  $control->SetAttribute( 'set_options',        $countries );
  $control->SetAttribute( 'set_empty_option',   true );

  $control->SetAttribute( 'set_postfix', '<br/>' );
  $control->SetLabelAttribute( 'set_position', 'prefix' );
  $control->SetLabelAttribute( 'set_label', 'Country' );


  $fieldset = $form->CreateFieldSet( 'fieldset', 'somename' );
  $control = $fieldset->CreateFormControl( 'submit', 'submit' );
  $control->SetAttribute( 'value', 'OK' );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function Demo()
{
 // $_POST['cnt_id'] = 99;

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

