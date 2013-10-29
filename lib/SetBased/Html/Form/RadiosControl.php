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
use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
class RadiosControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  public function setAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Common core attributes.
    case 'class':
    case 'id':
    case 'title':

      // Common internationalization attributes.
    case 'xml:lang':
    case 'dir':

      // Common event attributes.
    case 'onclick':
    case 'ondblclick':
    case 'onmousedown':
    case 'onmouseup':
    case 'onmouseover':
    case 'onmousemove':
    case 'onmouseout':
    case 'onkeypress':
    case 'onkeydown':
    case 'onkeyup':

      // Common style attribute.
    case 'style':

      // H2O Attributes
    case 'set_label_postfix':
    case 'set_label_prefix':
    case 'set_map_disabled':
    case 'set_map_key':
    case 'set_map_label':
    case 'set_map_obfuscator':
    case 'set_options':
    case 'set_postfix':
    case 'set_prefix':
    case 'set_value':

      $this->setAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->setAttributeBase( $theName, $theValue );
      }
      else
      {
        \SetBased\Html\Html::error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<div';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        // Element div does not have attribute 'name'. So, nothing to do.
        break;

      default:
        $ret .= \SetBased\Html\Html::generateAttribute( $name, $value );
      }
    }
    $ret .= ">\n";

    if (is_array($this->myAttributes['set_options']))
    {
      $map_key        = $this->myAttributes['set_map_key'];
      $map_label      = $this->myAttributes['set_map_label'];
      $map_disabled   = (isset($this->myAttributes['set_map_disabled']))   ? $this->myAttributes['set_map_disabled']   : null;
      $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

      $submit_name = $this->getSubmitName( $theParentName );
      foreach( $this->myAttributes['set_options'] as $option )
      {
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $option[$map_key] ) : $option[$map_key];

        $for_id = \SetBased\Html\Html::getAutoId();

        $input = "<input type='radio' name='$submit_name' value='$code' id='$for_id'";

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$option[$map_key])
        {
          $input .= " checked='checked'";
        }

        if ($map_disabled && !empty($option[$map_disabled])) $input .= " disabled='disabled'";

        $input .= "/>";

        $label  = (isset($this->myAttributes['set_label_prefix'])) ? $this->myAttributes['set_label_prefix'] : '';
        $label .= "<label for='$for_id'>";
        $label .= \SetBased\Html\Html::txt2Html( $option[$map_label] );
        $label .= "</label>";
        if (isset($this->myAttributes['set_label_postfix'])) $label .= $this->myAttributes['set_label_postfix'];

        $ret .= $input;
        $ret .= $label;
        $ret .= "\n";
      }
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
        $local_name = $this->myAttributes['name'];
        $theInvalidFormControls[$local_name] = true;

        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->encode( $local_name ) : $local_name;

    $map_key        = $this->myAttributes['set_map_key'];
    $map_disabled   = (isset($this->myAttributes['set_map_disabled']))   ? $this->myAttributes['set_map_disabled']   : null;
    $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted_value = (string)$theSubmittedValue[$submit_name];

      foreach( $this->myAttributes['set_options'] as $option )
      {
        // Get the (database) ID of the option.
        $id = $option[$map_key];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

        if ($submitted_value===(string)$code)
        {
          // If the orginal value differs from the submitted value then the form control has been changed.
          if (!isset($this->myAttributes['set_value']) ||
              $this->myAttributes['set_value']!==$id) $theChangedInputs[$local_name] = true;

          // Set the white listed value.
          $theWhiteListValue[$local_name]  = $id;
          $this->myAttributes['set_value'] = $id;

          // Leave the loop.
          break;
        }
      }
    }
    else
    {
      // No radio button has been checked.
      $theWhiteListValue[$local_name]  = null;
      $this->myAttributes['set_value'] = null;
    }

    if (!array_key_exists( $local_name, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $theWhiteListValue[$local_name] = (isset($this->myAttributes['set_value'])) ? $this->myAttributes['set_value'] : null;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    $this->myAttributes['set_value'] = $theValues[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
