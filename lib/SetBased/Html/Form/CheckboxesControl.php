<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 * @par Copyright:
 * Set Based IT Consultancy
 * $Date: 2013/03/04 19:02:37 $
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form;
use SetBased\Html\Html;

/**
 * @todo Implement disabled hard (can not be changed via javascript) and disabled sort (can be changed via javascript).
 */
class CheckboxesControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // A ControlCheckboxes must always have a name.
    if ($this->myName===false) Html::error( 'Name is empty' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $submit_name = $this->getSubmitName( $theParentName );

    $ret = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';

    $ret .= '<div';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    if (isset($this->myAttributes['set_options']))
    {
      if (is_array( $this->myAttributes['set_options'] ))
      {
        if (!isset($this->myAttributes['set_map_key'])) Html::error( "Not set mandatory attribute 'set_map_key'." );
        if (!isset($this->myAttributes['set_map_label'])) Html::error( "Not set mandatory attribute 'set_map_label'." );

        $map_key        = $this->myAttributes['set_map_key'];
        $map_label      = $this->myAttributes['set_map_label'];
        $map_id         = (isset($this->myAttributes['set_map_id'])) ? $this->myAttributes['set_map_id'] : null;
        $map_checked    = (isset($this->myAttributes['set_map_checked'])) ? $this->myAttributes['set_map_checked'] : 'set_map_checked';
        $map_disabled   = (isset($this->myAttributes['set_map_disabled'])) ? $this->myAttributes['set_map_disabled'] : null;
        $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

        foreach ($this->myAttributes['set_options'] as $option)
        {
          $code = ($map_obfuscator) ? $map_obfuscator->encode( $option[$map_key] ) : $option[$map_key];

          $id =  ($map_id && isset($option[$map_id]))?  $id = $option[$map_id] : Html::getAutoId();

          $input = "<input type='checkbox'";

          $input .= Html::generateAttribute( 'name', "${submit_name}[$code]" );

          $input .= Html::generateAttribute( 'id', $id );

          if ($map_checked && isset($option[$map_checked])) $input .= Html::generateAttribute( 'checked', $option[$map_checked] );
          {
            $input .= Html::generateAttribute( 'checked', $option[$map_checked] );
          }

          if ($map_disabled) $input .= Html::generateAttribute( 'disabled', $option[$map_disabled] );
          {
            $input .= Html::generateAttribute( 'disabled', $option[$map_checked] );
          }

          $input .= "/>";

          $label = isset($this->myAttributes['set_label_prefix']) ? $this->myAttributes['set_label_prefix'] : null; // optional
          $label .= "<label for='$id'>";
          $label .= Html::txt2Html( $option[$map_label] );
          $label .= "</label>";
          $label .= isset($this->myAttributes['set_label_postfix']) ? $this->myAttributes['set_label_postfix'] : null; // optional

          $ret .= $input;
          $ret .= $label;
          $ret .= "\n";
        }
      }
      else
      {
        Html::error( "Invalid attribute type given %s, expected array.", gettype( $this->myAttributes['set_options'] ) );
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
  /**
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control according to @a $theValues.
   *
   * @param $theValues array
   */
  public function setValuesBase( &$theValues )
  {
    if ($this->myName!=='') $values = & $theValues[$this->myName];
    else                    $values = & $theValues;

    $map_key     = $this->myAttributes['set_map_key'];
    $map_checked = $this->myAttributes['set_map_checked'];
    if (!$map_checked)
    {
      $this->myAttributes['set_map_checked'] = 'set_map_checked';
      $map_checked                           = 'set_map_checked';
      /** @todo More elegant handling of empty and default values */
    }

    foreach ($this->myAttributes['set_options'] as $id => $option)
    {
      $this->myAttributes['set_options'][$id][$map_checked] = !empty($values[$option[$map_key]]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate( $this );
      if ($valid!==true)
      {
        $theInvalidFormControls[$this->myName] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    $map_key        = $this->myAttributes['set_map_key'];
    $map_checked    = (isset($this->myAttributes['set_map_checked'])) ? $this->myAttributes['set_map_checked'] : 'set_map_checked';
    $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

    if (isset($theSubmittedValue[$submit_name]))
    {
      foreach ($this->myAttributes['set_options'] as $i => $option)

      {
        // Get the (database) ID of the option.
        $id = (string)$option[$map_key];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

        // Get the original value (i.e. the option is checked or not).
        $value = (isset($option[$map_checked])) ? $option[$map_checked] : false;

        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$submit_name][$code])) ? $theSubmittedValue[$submit_name][$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$this->myName][$id] = true;

        // Set the white listed value.
        $theWhiteListValue[$this->myName][$id]               = !empty($submitted);
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
