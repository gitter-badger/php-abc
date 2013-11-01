<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 *
 * @par Copyright:
 * Set Based IT Consultancy
 *
 * $Date: 2013/03/04 19:02:37 $
 *
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
   @todo Implement disabled hard (can not be changed via javascript) and disabled sort (can be changed via javascript).
 */
class CheckboxesControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // A ControlCheckboxes must always have a name.
    if ($this->myName===false) SetBased\Html\Html::error( 'Name is emtpy' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $submit_name = $this->getSubmitName( $theParentName );

    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    $ret .= '<div';
    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= \SetBased\Html\Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    if (isset($this->myAttributes['set_options']))
    {
      if (is_array($this->myAttributes['set_options']))
      {
        if(isset($this->myAttributes['set_map_key'])) $map_key = $this->myAttributes['set_map_key'];
        else Html::error( "Not set mandatory attribute 'set_map_key'." );

        if(isset($this->myAttributes['set_map_label'])) $map_label = $this->myAttributes['set_map_label'];
        else Html::error( "Not set mandatory attribute 'set_map_label'." );

        $map_id         = (isset($this->myAttributes['set_map_id'])) ? $this->myAttributes['set_map_id'] : null;
        $map_checked    = (isset($this->myAttributes['set_map_checked']))    ? $this->myAttributes['set_map_checked']    : null;
        $map_disabled   = (isset($this->myAttributes['set_map_disabled']))   ? $this->myAttributes['set_map_disabled']   : null;
        $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

        foreach( $this->myAttributes['set_options'] as $option )
        {
          $code = ($map_obfuscator) ? $map_obfuscator->encode( $option[$map_key] ) : $option[$map_key];

          if ($map_id && isset($option[$map_id])) $id = $option[$map_id];
          else                                    $id = \SetBased\Html\Html::getAutoId();

          $input = "<input type='checkbox'";

          $input .= \SetBased\Html\Html::generateAttribute( 'name', "${submit_name}[$code]" );

          $input .= \SetBased\Html\Html::generateAttribute( 'id', $id );

          if ($map_checked) $input .= \SetBased\Html\Html::generateAttribute( 'checked', $option[$map_checked] );

          if ($map_disabled) $input .= \SetBased\Html\Html::generateAttribute( 'disabled', $option[$map_checked] );

          $input .= "/>";

          $label  = isset($this->myAttributes['set_label_prefix']) ? $this->myAttributes['set_label_prefix'] : null;  // optional
          $label .= "<label for='$id'>";
          $label .= \SetBased\Html\Html::txt2Html( $option[$map_label] );
          $label .= "</label>";
          $label .= isset($this->myAttributes['set_label_postfix']) ? $this->myAttributes['set_label_postfix'] : null;  // optional

          $ret .= $input;
          $ret .= $label;
          $ret .= "\n";
        }
      }
      else
      {
        Html::error( "Invalid attribute type given %s, expected array.", gettype($this->myAttributes['set_options']) );
      }
    }
    else
    {
      Html::error( "Not set mandatory attribute 'set_options'." );
    }

    $ret .= "</div>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function validateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach( $this->myValidators as $validator )
    {
      $valid = $validator->validate( $this );
      if ($valid!==true)
      {
        $theInvalidFormControls[$this->myName] = true;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    if ($this->myName!==false) $values = &$theValues[$this->myName];
    else                       $values = &$theValues;

    $map_key     = $this->myAttributes['set_map_key'];
    $map_checked = $this->myAttributes['set_map_checked'];
    if (!$map_checked)
    {
      $this->myAttributes['set_map_checked'] = 'set_map_checked';
      $map_checked                           = 'set_map_checked';
      /** @todo More elegant handling of empty and default values */
    }

    $checked = array();
    foreach( $values as $value )
    {
      $checked[$value[$map_key]] = true;
    }

    foreach( $this->myAttributes['set_options'] as $id => $option )
    {
      $this->myAttributes['set_options'][$id][$map_checked] = $checked[$option[$map_key]];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $submit_name = ($obfuscator) ? $obfuscator->encode( $this->myName ) : $this->myName;

    $map_key        = $this->myAttributes['set_map_key'];
    $map_checked    = (isset($this->myAttributes['set_map_checked']))    ?  $this->myAttributes['set_map_checked']    : 'set_map_checked';
    $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ?  $this->myAttributes['set_map_obfuscator'] : null;

    if (isset($theSubmittedValue[$submit_name]))
    {
      foreach( $this->myAttributes['set_options'] as $i => $option )
      {
        // Get the (database) ID of the option.
        $id = $option[$map_key];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

        // Get the orginal value (i.e. the option is checked or not).
        $value = (isset($option[$map_checked])) ? $option[$map_checked] : false;

        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$submit_name][$code])) ? $theSubmittedValue[$submit_name][$code] : false;

        // If the orginal value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$this->myName][$id] = true;

        // Set the white listed value.
        $theWhiteListValue[$this->myName][$id]                 = !empty($submitted);
        $this->myAttributes['set_options'][$i][$map_checked] = !empty($submitted);
      }
    }
    else
    {
      // No checkboxes have been checked.
      $theWhiteListValue[$this->myName] = array();
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
