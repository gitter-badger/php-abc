<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class RadiosControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $ret = $this->myPrefix;

    $ret .= '<div';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    if (is_array( $this->myAttributes['set_options'] ))
    {
      $map_key        = $this->myAttributes['set_map_key'];
      $map_label      = $this->myAttributes['set_map_label'];
      $map_disabled   = (isset($this->myAttributes['set_map_disabled'])) ? $this->myAttributes['set_map_disabled'] : null;
      $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

      $submit_name = $this->getSubmitName( $theParentName );
      foreach ($this->myAttributes['set_options'] as $option)
      {
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $option[$map_key] ) : $option[$map_key];

        $for_id = Html::getAutoId();

        $input = "<input type='radio' name='$submit_name' value='$code' id='$for_id'";

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$option[$map_key])
        {
          $input .= " checked='checked'";
        }

        if ($map_disabled && !empty($option[$map_disabled]))
        {
          $input .= " disabled='disabled'";
        }

        $input .= "/>";

        $label = (isset($this->myAttributes['set_label_prefix'])) ? $this->myAttributes['set_label_prefix'] : '';
        $label .= "<label for='$for_id'>";
        $label .= Html::txt2Html( $option[$map_label] );
        $label .= "</label>";
        if (isset($this->myAttributes['set_label_postfix']))
        {
          $label .= $this->myAttributes['set_label_postfix'];
        }

        $ret .= $input;
        $ret .= $label;
        $ret .= "\n";
      }
    }

    $ret .= "</div>";
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function validateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate( $this );
      if ($valid!==true)
      {
        $theInvalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    $map_key        = $this->myAttributes['set_map_key'];
    $map_disabled   = (isset($this->myAttributes['set_map_disabled'])) ? $this->myAttributes['set_map_disabled'] : null;
    $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted_value = (string)$theSubmittedValue[$submit_name];

      foreach ($this->myAttributes['set_options'] as $option)
      {
        // Get the (database) ID of the option.
        $id = $option[$map_key];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

        if ($submitted_value===(string)$code)
        {
          // If the original value differs from the submitted value then the form control has been changed.
          if (!isset($this->myAttributes['set_value']) ||
            $this->myAttributes['set_value']!==$id
          )
          {
            $theChangedInputs[$this->myName] = $this;
          }

          // Set the white listed value.
          $theWhiteListValue[$this->myName] = $id;
          $this->myAttributes['set_value']  = $id;

          // Leave the loop.
          break;
        }
      }
    }
    else
    {
      // No radio button has been checked.
      $theWhiteListValue[$this->myName] = null;
      $this->myAttributes['set_value']  = null;
    }

    if (!array_key_exists( $this->myName, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $theWhiteListValue[$this->myName] = (isset($this->myAttributes['set_value'])) ? $this->myAttributes['set_value'] : null;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    $this->myAttributes['set_value'] = $theValues[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
