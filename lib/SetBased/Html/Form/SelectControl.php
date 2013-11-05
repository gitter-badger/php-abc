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
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function setAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'multiple':
      // case 'name':
    case 'size':
      // case 'type':

      // Advanced attributes.
    case 'disabled':
    case 'onblur':
    case 'onchange':
    case 'onfocus':
    case 'onselect':
    case 'tabindex':

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
    case 'set_empty_option':
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
  /** @todo Implement 'multiple'.
   */
  public function generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<select';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        $submit_name = $this->getSubmitName( $theParentName );
        $ret .= \SetBased\Html\Html::generateAttribute( $name, $submit_name );
        break;

      default:
        $ret .= \SetBased\Html\Html::generateAttribute( $name, $value );
      }
    }
    $ret .= ">\n";


    if (!empty($this->myAttributes['set_empty_option']))
    {
      $ret .= "<option value=' '></option>\n";
    }

    if (is_array($this->myAttributes['set_options']))
    {
      $map_key        = $this->myAttributes['set_map_key'];
      $map_label      = $this->myAttributes['set_map_label'];
      $map_disabled   = (isset($this->myAttributes['set_map_disabled']))   ? $this->myAttributes['set_map_disabled']   : null;
      $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

      foreach( $this->myAttributes['set_options'] as $option )
      {
        // Get the (database) ID of the option.
        $id = $option[$map_key];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

        //
        $ret .= "<option value='$code'";

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$id)
        {
          $ret .= " selected='selected'";
        }

        if ($map_disabled && !empty($option[$map_disabled])) $ret .= " disabled='disabled'";

        $ret .= ">";
        $ret .= \SetBased\Html\Html::txt2Html( $option[$map_label] );
        $ret .= "</option>\n";
      }
    }

    $ret .= "</select>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
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
      $submitted = (string)$theSubmittedValue[$submit_name];

      // Normalize default value as a string.
      $value = isset($this->myAttributes['set_value']) ? (string)$this->myAttributes['set_value'] : '';

      if (empty($this->myAttributes['set_empty_option']) && $submitted===' ')
      {
        $theWhiteListValue[$local_name] = null;
        if ($value!=='' && $value!==' ') $theChangedInputs[$local_name] = true;
      }
      else
      {
        if (is_array($this->myAttributes['set_options']))
        {
          foreach( $this->myAttributes['set_options'] as $option )
          {
            // Get the (database) ID of the option.
            $id = $option[$map_key];

            // If an obfuscator is installed compute the obfuscated code of the (database) ID.
            $code = ($map_obfuscator) ? $map_obfuscator->encode( $id ) : $id;

            if ($submitted===(string)$code)
            {
              // If the orginal value differs from the submitted value then the form control has been changed.
              if ($value!==(string)$id) $theChangedInputs[$local_name] = true;

              // Set the white listed value.
              $this->myAttributes['set_value'] = $id;
              $theWhiteListValue[$local_name]  = $id;

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
      $theWhiteListValue[$local_name] = null;
      if ($value!=='' && $value!==' ') $theChangedInputs[$local_name] = true;
    }

    if (!array_key_exists( $local_name, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $theWhiteListValue[$local_name] = isset($this->myAttributes['set_value']) ? $this->myAttributes['set_value'] : null;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    /** @todo check on type and value is in list of options. */
    $local_name = $this->myAttributes['name'];
    $this->myAttributes['set_value'] = $theValues[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
