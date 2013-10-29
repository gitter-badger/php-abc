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
class TextAreaControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myAttributes['set_clean'] = '\SetBased\Html\Clean::trimWhitespace';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Set the maximum number of characters the user may enter. This number should not exceed
    * the value specified in the size attribute.
    */
  /** Sets the Web browser the initial width of the control, its value is the number of characters.
    */
  /** Sets the class name or set of class names to an element. Any number of elements may be
    * assigned the same class name or set of class names. Multiple class names must be separated by white space
    * characters. Class names are typically used to apply CSS formatting rules to an element.
    */
  /** Sets the value associated with the control.
    */
  /** Adds a class name or set of class names to an element.
    */
  /** Set the ID of the element. This ID must be unique in a document. This ID can be used by
    * client-side scripts (such as JavaScript) to select elements, apply CSS formatting rules, or to build
    * relationships between elements.
    */
  public function setAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'cols':
    case 'rows':
    case 'name':

      // Advanced attributes.
    case 'accesskey':
    case 'disabled':
    case 'onblur':
    case 'onchange':
    case 'onfocus':
    case 'onselect':
    case 'readonly':
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
    case 'set_clean':
    case 'set_text':
    case 'set_prefix':
    case 'set_postfix':

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
    $ret .= '<textarea';

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
    $ret .= ">";

    if (!empty($this->myAttributes['set_text'])) $ret .= \SetBased\Html\Html::txt2Html( $this->myAttributes['set_text'] );
    $ret .= "</textarea>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->encode( $local_name ) : $local_name;

    if (isset($this->myAttributes['set_clean']))
    {
      $new_value = call_user_func( $this->myAttributes['set_clean'], $theSubmittedValue[$submit_name] );
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }
    // Normalize old (original) value and new (submitted) value.
    $old_value = (isset($this->myAttributes['value'])) ? $this->myAttributes['value'] : null;
    if ($old_value==='' || $old_value===null || $old_value===false) $old_value = '';
    if ($new_value==='' || $new_value===null || $new_value===false) $new_value = '';

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$local_name]  = true;
      $this->myAttributes['set_text'] = $new_value;
    }

    $theWhiteListValue[$local_name] = $new_value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:hidden must be a scalar.
      if (!is_scalar($value))
      {
        \SetBased\Html\Html::error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['set_text'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['set_text']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
