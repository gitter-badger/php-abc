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
  echo "<title>Sample Select</title>\n";
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
  $legend->SetAttribute( 'set_inline', 'Other' );

  $countries[] = array( 'cnt_id' => '1', 'cnt_name' => 'NL' );
  $countries[] = array( 'cnt_id' => '2', 'cnt_name' => 'BE' );
  $countries[] = array( 'cnt_id' => '3', 'cnt_name' => 'LU' );
  $countries[] = array( 'cnt_id' => '4', 'cnt_name' => 'UA' );

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
  //$_POST['cnt_id'] = 4;
  //$_POST['cnt_name'] ='MOON';

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

