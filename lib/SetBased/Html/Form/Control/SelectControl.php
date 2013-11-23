<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /** @todo Implement 'multiple'.
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    $ret .= '<select';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";


    if (!empty($this->myAttributes['set_empty_option']))
    {
      $ret .= "<option value=' '></option>\n";
    }

    if (is_array( $this->myAttributes['set_options'] ))
    {
      $map_key        = $this->myAttributes['set_map_key'];
      $map_label      = $this->myAttributes['set_map_label'];
      $map_disabled   = (isset($this->myAttributes['set_map_disabled'])) ? $this->myAttributes['set_map_disabled'] : null;
      $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

      foreach ($this->myAttributes['set_options'] as $option)
      {
        // Get the (database) ID of the option.
        $id = (string)$option[$map_key];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

        //
        $ret .= "<option value='$code'";

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$id)
        {
          $ret .= " selected='selected'";
        }

        if ($map_disabled && !empty($option[$map_disabled]))
        {
          $ret .= " disabled='disabled'";
        }

        $ret .= ">";
        $ret .= Html::txt2Html( $option[$map_label] );
        $ret .= "</option>\n";
      }
    }

    $ret .= "</select>";

    if (isset($this->myAttributes['set_postfix']))
    {
      $ret .= $this->myAttributes['set_postfix']."\n";
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    $map_key        = $this->myAttributes['set_map_key'];
    $map_disabled   = (isset($this->myAttributes['set_map_disabled'])) ? $this->myAttributes['set_map_disabled'] : null;
    $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

    // Normalize default value as a string.
    $value = isset($this->myAttributes['set_value']) ? (string)$this->myAttributes['set_value'] : '';

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted = (string)$theSubmittedValue[$submit_name];

      if (empty($this->myAttributes['set_empty_option']) && $submitted===' ')
      {
        $theWhiteListValue[$this->myName] = null;
        if ($value!=='' && $value!==' ')
        {
          $theChangedInputs[$this->myName] = $this;
        }
      }
      else
      {
        if (is_array( $this->myAttributes['set_options'] ))
        {
          foreach ($this->myAttributes['set_options'] as $option)
          {
            // Get the (database) ID of the option.
            $id = $option[$map_key];

            // If an obfuscator is installed compute the obfuscated code of the (database) ID.
            $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

            if ($submitted===(string)$code)
            {
              // If the original value differs from the submitted value then the form control has been changed.
              if ($value!==(string)$id)
              {
                $theChangedInputs[$this->myName] = $this;
              }

              // Set the white listed value.
              $this->myAttributes['set_value']  = $id;
              $theWhiteListValue[$this->myName] = $id;

              // Leave the loop.
              break;
            }
          }
        }
      }
    }
    else
    {
      // No value has been submitted.
      $theWhiteListValue[$this->myName] = null;
      if ($value!=='' && $value!==' ')
      {
        $theChangedInputs[$this->myName] = $this;
      }
    }

    if (!array_key_exists( $this->myName, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $theWhiteListValue[$this->myName] = isset($this->myAttributes['set_value']) ? $this->myAttributes['set_value'] : null;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    /** @todo check on type and value is in list of options. */
    $this->myAttributes['set_value'] = (string)$theValues[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
