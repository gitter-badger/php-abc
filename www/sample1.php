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
  $legend->SetAttribute( 'h2o_inline', 'Name' );

  $control = $fieldset->CreateFormControl( 'text', 'first_name' );
  $control->SetAttribute( 'h2o_postfix', '<br/>' );
  $control->SetLabelAttribute( 'h2o_position', 'prefix' );
  $control->SetLabelAttribute( 'h2o_label', 'first  name' );

  $control = $fieldset->CreateFormControl( 'text', 'last_name' );
  $control->SetAttribute( 'h2o_postfix', '<br/>' );
  $control->SetLabelAttribute( 'h2o_position', 'prefix' );
  $control->SetLabelAttribute( 'h2o_label', 'last  name' );


  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'h2o_inline', 'URI' );

  $control = $fieldset->CreateFormControl( 'text', 'email' );
  $control->SetAttribute( 'h2o_postfix', '<br/>' );
  $control->SetLabelAttribute( 'h2o_position', 'prefix' );
  $control->SetLabelAttribute( 'h2o_label', 'email' );
  $control->AddValidator( new H2O_HtmlFormControlValidatorEmail() );

  $control = $fieldset->CreateFormControl( 'text', 'url' );
  $control->SetAttribute( 'h2o_postfix', '<br/>' );
  $control->SetLabelAttribute( 'h2o_position', 'prefix' );
  $control->SetLabelAttribute( 'h2o_label', 'url' );
  $control->AddValidator( new H2O_HtmlFormControlValidatorHttp() );

  $fieldset = $form->CreateFieldSet();
  $legend = $fieldset->CreateLegend();
  $legend->SetAttribute( 'h2o_inline', 'Other' );

  $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
  $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
  $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );

  $control = $fieldset->CreateFormControl( 'select', 'cnt_id' );

  $control->SetAttribute( 'h2o_map_key',        'cnt_id' );
  $control->SetAttribute( 'h2o_map_label',      'cnt_name' );
  $control->SetAttribute( 'h2o_value',          '1' );
  $control->SetAttribute( 'h2o_options',        $countries );
  $control->SetAttribute( 'h2o_empty_option',   true );

  $control->SetAttribute( 'h2o_postfix', '<br/>' );
  $control->SetLabelAttribute( 'h2o_position', 'prefix' );
  $control->SetLabelAttribute( 'h2o_label', 'Country' );


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

