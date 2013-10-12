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
require_once( __DIR__.'/html.php' );

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlClean
{
  //--------------------------------------------------------------------------------------------------------------------
  public static function PruneWhitespace( $theValue )
  {
    if (empty($theValue)) return $theValue;
    else                  return trim( mb_ereg_replace( '[\ \t\n\r\0\x0B\xA0]+', ' ', $theValue, 'p' ) );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function TrimWhitespace( $theValue )
  {
    if (empty($theValue)) return $theValue;
    else                  return trim( $theValue, " \t\n\r\0\x0B\xA0" );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function NormalizeUrl( $theValue )
  {
    $value = self::TrimWhitespace( $theValue );

    if ($value===null || $value===false || $value==='') return false;

    $parts = parse_url( $value );
    if (!is_array( $parts )) return false;

    if (sizeof( $parts )==1 && isset( $parts['path']))
    {
      $i = strpos( $parts['path'], '/' );
      if ($i===false)
      {
        $parts['host'] = $parts['path'];
        unset( $parts['path'] );
      }
      else
      {
        $parts['host'] = substr( $parts['path'], 0, $i );
        $parts['path'] = substr( $parts['path'], $i );
      }
    }

    if (isset($parts['scheme']))
    {
      $sep = (strtolower($parts['scheme']) == 'mailto' ? ':' : '://');
      $url = strtolower( $parts['scheme'] ).$sep;
    }
    else
    {
      $url = 'http://';
    }

    if (!$parts['path'] && strtolower( $parts['scheme'] )!='mailto')
    {
      $parts['path'] = '/';
    }

    if (isset($parts['pass']))
    {
      $url .= "$parts[user]:$parts[pass]@";
    }
    elseif (isset($parts['user']))
    {
      $url .= "$parts[user]@";
    }

    if (isset($parts['host']))     $url .= $parts['host'];
    if (isset($parts['port']))     $url .= ':'.$parts['port'];
    if (isset($parts['path']))     $url .= $parts['path'];
    if (isset($parts['query']))    $url .= '?'.$parts['query'];
    if (isset($parts['fragment'])) $url .= '#'.$parts['fragment'];

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public static function TidyHtml( $theValue )
  {
    $value = self::TrimWhitespace( $theValue );

    if ($value===null || $value===false || $value==='') return false;

    $tidy_config = array( 'clean'          => false,
                          'output-xhtml'   => true,
                          'show-body-only' => true,
                          'wrap'           => 100 );

    $tidy = new tidy;

    $tidy->parseString( $value, $tidy_config, 'utf8' );
    $tidy->cleanRepair();
    $value = trim( tidy_get_output( $tidy ) );

    if (preg_match( '/^(([\ \r\n\t])|(<p>)|(<\/p>)|(&nbsp;))*$/', $value )==1)
    {
      $value = false;
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Interface for defining classes for obfuscation and deobfuscating database ID.
 */
interface SET_HtmlObfuscator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the obfuscate value of @a $theValue.
   */
  public function Encode( $theValue );

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the deobfuscate value of @a $theCode.
   */
  public function Decode( $theCode );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Interface for defining classes that validate form control elements direved from SET_HtmlFormControl.
 */
interface SET_HtmlFormControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  public function Validate( $theFormControl );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Validates if a form control has a value.
 *
 *  @note Can be applied on any form control object.
 */
class SET_HtmlFormControlValidatorMandatory implements SET_HtmlFormControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Validates recursively if one of the leaves of @a $theArray has a non-empty value.
      @param $theArray A nested array.
   */
  private function ValidateArray( $theArray )
  {
    foreach( $theArray as $element )
    {
      if (is_scalar( $element ))
      {
        if ($element!==null && $element!==false && $element!=='') return true;
      }
      else
      {
        $tmp = $this->ValidateArray( $element );
        if ($tmp===true) return true;
      }
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Validate( $theFormControl )
  {
    $value = $theFormControl->GetSubmittedValue();

    if ($value===null || $value===false || $value==='') return false;

    if (is_array($value)) return $this->ValidateArray( $value );

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Validates if the value of a form control (derived from SET_HtmlFormControl) is a valid email address.
 *
 *  @note Can only be applied on form controls which values are strings.
 */
class SET_HtmlFormControlValidatorInteger implements SET_HtmlFormControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** The lower bound of the range of valid (integer) values.
   */
  private $myMinValue;

  /** The upper bound of the range of valid (integer) values.
   */
  private $myMaxValue;

  //--------------------------------------------------------------------------------------------------------------------
  /** Object constructor.
   */
  public function __construct( $theMinValue=null, $theMaxValue=PHP_INT_MAX )
  {
    $this->myMinValue = (isset($theMinValue)) ? $theMinValue : -PHP_INT_MAX;
    $this->myMaxValue = $theMaxValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @c true if @a $theFormControl has no value.
   *  Returns @c true if the value of @a $theFormControl is an integer.
   *  Otherwise returns @c false.
   */
  public function Validate( $theFormControl )
  {
    $options = array( 'options' => array( 'min_range' => $this->myMinValue,
                                          'max_range' => $this->myMaxValue ) );

    $value = $theFormControl->GetSubmittedValue();

    // An empty value is valid.
    if ($value===null || $value===false || $value==='') return true;

    // Objects and arrays are not an integer.
    if (!is_scalar($value)) return false;

    // Filter valid integer values with valid range.
    $integer = filter_var( $value, FILTER_VALIDATE_INT, $options );

    // If the actual value and the filtered value are not equal the value is not an integer.
    if ((string)$integer!==(string)$value) return false;

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Validates if the value of a form control (derived from SET_HtmlFormControl) is a valid email address.
 *
 *  @note Can only be applied on form controls which values are strings.
 */
class SET_HtmlFormControlValidatorEmail implements SET_HtmlFormControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @a true if @a $theFormControl has no value.
   *  Returns @a true if the value of @a $theFormControl is a valid email address. The format of the email address
   *  is valided as well if the domain in the email address realy exists.
   *  Otherwise returns @a false.
   */
  public function Validate( $theFormControl )
  {
    $value = $theFormControl->GetSubmittedValue();

    // An empty value is valid.
    if ($value===null || $value===false || $value==='') return true;

    // Objects and arrays are not valid email addresses.
    if (!is_scalar($value)) return false;

    // Filter valid email address from the value.
    $email = filter_var( $value, FILTER_VALIDATE_EMAIL );

    // If the actual value and the filtered value are not equal the value is not a valid email address.
    if ($email!==$value) return false;

    // Test if the domain does exists.
    list ( $local, $domain ) = explode( '@', $value, 2 );
    if ($domain===null) return false;

    // The domain must have a MX or A record.
    if (!(checkdnsrr( $domain.'.', 'MX' ) || checkdnsrr( $domain.'.', 'A' ))) return false;

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Validates if the value of a form control (derived from SET_HtmlFormControl) is a valid http URL.
 *
 * @note Can only be applied on form controls which values are strings.
 */
class SET_HtmlFormControlValidatorHttp implements SET_HtmlFormControlValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @a true if @a $theFormControl has no value.
   *  Returns @a true if the value of @a $theFormControl is a valid http URL.
   *  Otherwise returns @a false.
   */
  public function Validate( $theFormControl )
  {
    $value = $theFormControl->GetSubmittedValue();

    // An empty value is valid.
    if ($value===null || $value===false || $value==='') return true;

    // Objects and arrays are not a valid http URL.
    if (!is_scalar($value)) return false;

    // Filter valid URL from the value.
    $url = filter_var( $value, FILTER_VALIDATE_URL );

    // If the actual value and the filtered value are not equal the value is not a valid url.
    if ($url!==$value) return false;

    // filter_var allows not to specify the HTTP protocol. Test the URL starts with http (or https).
    if (substr( $url, 0, 4 )!=='http') return false;

    // Test that the page actually exits. We consider all HTTP 200-399 responses are valid.
    $hdrs = get_headers( $url );
    $ok   = (is_array($hdrs) && preg_match('/^HTTP\\/\\d+\\.\\d+\\s+[23]\\d\\d\\s*.*$/',$hdrs[0]));

    return $ok;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Abstract class for objects for generation HTML code for form controls.
 */
abstract class SET_HtmlFormControl
{
  protected $myValidators = array();

  protected $myAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  /** Object creator. Creates a form control with (local) name @a $theName.
   */
  public function __construct( $theName )
  {
    if ($theName===null || $theName===false || $theName==='')
    {
      // We consider null, bool(false), and string(0) as empty. In these cases we set the name to false such that
      // we only have to test against false using the === operator in other parts of the code.
      $this->myAttributes['name'] = false;
    }
    else
    {
      // We consider int(0), float(0), string(0) "", string(3) "0.0" as non empty names.
      $this->myAttributes['name'] = (string)$theName;
    }

    /** @todo Consider throw exception when name is not a scalar or set name to false.
     */
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Adds validator @a $theValidator to this form control.
   */
  public function AddValidator( $theValidator )
  {
    $this->myValidators[] = $theValidator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Helper function for SET_HtmlFormControl::SetAttribute.
      Sets the value attribute with name @a $theName to @a $theValue. If @a $theValue is @c null, @c false, or @c ''
      the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
   */
  protected function SetAttributeBase( $theName, $theValue )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myAttributes[$theName] );
    }
    else
    {
      if ($theName==='class' && isset($this->myAttributes[$theName]))
      {
        $this->myAttributes[$theName] .= ' ';
        $this->myAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Sets the value of attribute with name @a $theName of this form control to @a $theValue. If @a $theValue is
      @c null, @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
      @todo Document @a theExtendedFlag
   */
  abstract public function SetAttribute( $theName, $theValue, $theExtendedFlag=false );

  //--------------------------------------------------------------------------------------------------------------------
  public function GetAttribute( $theName )
  {
    return (isset($this->myAttributes[$theName])) ? $this->myAttributes[$theName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  abstract public function Generate( $theParentName );

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the local name of this form control
   */
  public function GetLocalName()
  {
    return $this->myAttributes['name'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GetSubmitName( $theParentSubmitName )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if ($theParentSubmitName!==false)
    {
      if ($submit_name!==false) $global_name = $theParentSubmitName.'['.$submit_name.']';
      else                      $global_name = $theParentSubmitName;
    }
    else
    {
      $global_name = $submit_name;
    }

    return $global_name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function GetErrorMessages( $theRecursiveFlag=false )
  {
    return (isset($this->myAttributes['set_errmsg'])) ? $this->myAttributes['set_errmsg'] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the submitted value of this form control.
   */
  public function GetSubmittedValue()
  {
    return $this->myAttributes['set_submitted_value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetErrorMessage( $theMessage )
  {
    $this->myAttributes['set_errmsg'][] = $theMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  abstract public function SetValuesBase( &$theValues );

  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function ValidateBase( &$theInvalidFormControls );

  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** Class for generating form control elements of type
  * \li text
  * \li password
  * \li hidden
  * \li checkbox
  * \li radio
  * \li submit
  * \li reset
  * \li button
  * \li file
  * \li image
  */

abstract class SET_HtmlFormControlSimple extends SET_HtmlFormControl
{
  protected $myLabelAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  /** Creates a SET_HtmlFormControlSimple object for generating a form control element of @a $theType with (local)
    * name \a $theName.
    */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // A simple form control must have a name.
    $local_name = $this->myAttributes['name'];
    if ($local_name===false) SET_Html::Error( 'Name is emtpy' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetLabelAttribute( $theName, $theValue, $theExtendedFlag=false  )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myLabelAttributes[$theName] );
      return;
    }

    switch ($theName)
    {
      // Basic attributes.
    // case 'for':

      // Advanced attributes.
    case 'accesskey':
    case 'onblur':
    case 'onfocus':

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
    case 'set_label':
    case 'set_position':
    case 'set_prefix':
    case 'set_postfix':

      if ($theName==='class' && isset($this->myLabelAttributes[$theName]))
      {
        $this->myLabelAttributes[$theName] .= ' ';
        $this->myLabelAttributes[$theName] .= (string)$theValue;
      }
      else
      {
        $this->myLabelAttributes[$theName] = (string)$theValue;
      }
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->myLabelAttributes[$theName] = (string)$theValue;
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GeneratePrefixLabel()
  {
    $ret = false;

    if (isset($this->myLabelAttributes['set_position']))
    {
      if ($this->myAttributes['id']=='')
      {
        $id = SET_Html::GetAutoId();
        $this->myAttributes['id']       = $id;
        $this->myLabelAttributes['for'] = $id;
      }
      else
      {
        $this->myLabelAttributes['for'] = $this->myAttributes['id'];
      }

      if ($this->myLabelAttributes['set_position']=='prefix') $ret .= $this->GenerateLabel();
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GeneratePostfixLabel()
  {
    if (isset($this->myLabelAttributes['set_position']) && $this->myLabelAttributes['set_position']=='postfix')
    {
      $ret = $this->GenerateLabel();
    }
    else
    {
      $ret = false;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateLabel()
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<label';

    foreach( $this->myLabelAttributes as $index => $value )
    {
      switch ($index)
      {
       // Common core attributes
      case 'for':
        if ($value!='') $ret .= " $index='$value'";
        break;


      case 'class':
      case 'id':
      case 'title':
        if ($value!='') $ret .= " $index='$value'";
        break;


      case 'xml:lang':
      case 'dir':
        if ($value!='') $ret .= " $index='$value'";
        break;


      // Common event attributes
      case 'onclick':
      case 'ondbclick':
      case 'onmousedown':
      case 'onmouseup':
      case 'onmouseover':
      case 'onmouseout':
      case 'onkeypress':
      case 'ononkeydown':
      case 'onkeyup':
        return SET_Html::Error( 'not implemented' );
        break;


      // Common style attribute
      case 'style':
        if ($value!='') $ret .= " $index='$value'";
        break;
      }
    }
    $ret .= ">";

    $ret .= $this->myLabelAttributes['set_label'];
    $ret .= "</label>";

    $ret .= $this->myLabelAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function ValidateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach( $this->myValidators as $validator )
    {
      $valid = $validator->Validate( $this );
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
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:text.
 */
class SET_HtmlFormControlText extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myAttributes['set_clean'] = 'SET_HtmlClean::PruneWhitespace';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Sets attribute \a $theName to value \a $theValue.
    * The following attributes are supported.
    * <ul>
    * <li>Basic attributes
    *   <ul>
    *   <li>\a alt Alternate text for controls of the type image.
    *   <li>\a checked When the type attribute has the value radio or checkbox, this attribute specifies that the
    *       radio/checkbox is selected. Any none empty value indicates that radio/checkbox is selected.
    *   <li>\a maxlength (Number) When the type attribute has the value text or password, this attribute specifies the
    *       maximum number of characters the user may enter. This number should not exceed the value specified in
    *       the size attribute..
    *   <li>\a size (Number) This attribute tells the Web browser the initial width of the control. The width is given
    *       in pixels except when the type attribute has the value text or password. In such cases, its value is
    *       the number of characters.
    *   <li>\a value (Text) Value associated with a control.
    *   </ul>
    *  <li>Advanced Attributes
    *    <ul>
    *   <li>\a accept (ContentTypes) This attribute specifies a comma-separated list of content types that a server
    *       processing this form will handle correctly.
    *   <li>\a accesskey (Character) Accessibility key character.
    *   <li>\a disabled Disables the control for user input. Any none empty value indicates that the control is
    *       disabled.
    *   <li>\a ismap If present, this attribute specifies that a server-side image map should be used. Possible value
    *       is ismap.
    *   <li>\a onblur (Script) A client-side script event that occurs when an element loses focus either by the
    *        pointing device or by tabbing navigation.
    *   <li>\a onchange (Script) A client-side script event that occurs when a control loses the input focus and
    *       its value is modified prior to its next receiving focus.
    *   <li>\a onfocus (Script) A client-side script event that occurs when an element receives focus either by the
    *       pointing device or by tabbing navigation.
    *   <li>\a onselect (Script) A client-side script event that occurs when a user selects some text in a text field.
    *   <li>\a readonly If present, this attribute prohibits changes to the value in the control. Possible value is readonly.
    *   <li>\a src (URI) When the type attribute has the value image, this attribute specifies the location of the
    *       image to be used to decorate the graphical submit button.
    *   <li>\a tabindex (Number) Position in tabbing order.
    *   <li>\a usemap (IDReference) When the type attribute has the value image, this attribute associates the image
    *        to a client-side image map defined by a map element. The value of this attribute must match the id
    *        attribute of the map element.
    *   </ul>
    *   <li>Common core attributes
    *   <ul>
    *   <li>\a class (NameTokens) This attribute assigns a class name or set of class names to an element. Any
    *       number of elements may be assigned the same class name or set of class names. Multiple class names must
    *       be separated by white space characters. Class names are typically used to apply CSS formatting rules to
    *       an element.
    *   <li>\a id (ID) This attribute assigns an ID to an element. This ID must be unique in a document. This ID can
    *       be used by client-side scripts (such as JavaScript) to select elements, apply CSS formatting rules, or
    *       to build relationships between elements.
    *   <li>\a title (Text) This attribute offers advisory information. Some Web browsers will display this
    *       information as tooltips. Assistive technologies may make this information available to users as
    *       additional information about the element.
    *   </ul>
    *   <li>Common internationalization attributes
    *   <ul>
    *   <li>\a xml:lang (NameToken) This attribute specifies the base language of an element's attribute values and
    *       text content.
    *   <li>\a dir This attribute specifies the base direction of text. Possible values:
    *        ltr: Left-to-right
             rtl: Right-to-left
    *   </ul>
    *   <li>Common event attributes
    *   <ul>
    *   <li>\a onclick (Script) A client-side script event that occurs when a pointing device button is clicked over an element.
    *   <li>\a ondblclick (Script) A client-side script event that occurs when a pointing device button is double-clicked over an element.
    *   <li>\a onmousedown (Script) A client-side script event that occurs when a pointing device button is pressed down over an element.
    *   <li>\a onmouseup (Script) A client-side script event that occurs when a pointing device button is released over an element.
    *   <li>\a onmouseover (Script) A client-side script event that occurs when a pointing device is moved onto an element.
    *   <li>\a onmousemove (Script) A client-side script event that occurs when a pointing device is moved within an element.
    *   <li>\a onmouseout (Script) A client-side script event that occurs when a pointing device is moved away from an element.
    *   <li>\a onkeypress (Script) A client-side script event that occurs when a key is pressed down over an element then released.
    *   <li>\a onkeydown (Script) A client-side script event that occurs when a key is pressed down over an element.
    *   <li>\a onkeyup (Script) A client-side script event that occurs when a key is released over an element.
    *   </ul>
    *   <li>Common style attribute
    *   <ul>
    *   <li>\a style (Text) This attribute specifies formatting style information for the current element. The
    *       content of this attribute is called inline CSS. The style attribute is deprecated (considered outdated),
    *       because it fuses together content and formatting.
    *   </ul>
    *   <li> H2O Attributes
    *   <ul>
    *   <li>\a set_prefix (html) Code placed before the code of the form control object.
    *   <li>\a set_postfix (html) Code placed after the code of the form control object.
    *   </ul>
    *   </ul>
    */
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'maxlength':
    // case 'name':
    case 'size':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'set_prefix':
    case 'set_postfix':
    case 'set_clean':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'text' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        case 'size':
          if (isset($this->myAttributes['maxlength'])) $value = min( $value, $this->myAttributes['maxlength'] );
          $ret .= SET_Html::GenerateAttribute( $name, $value );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if (isset($this->myAttributes['set_clean'])) $new_value = call_user_func( $this->myAttributes['set_clean'], $theSubmittedValue[$submit_name] );
    else                                         $new_value = $theSubmittedValue[$submit_name];

    // Normalize old (original) value and new (submitted) value.
    $old_value = (isset($this->myAttributes['value'])) ? $this->myAttributes['value'] : null;
    if ($old_value==='' || $old_value===null || $old_value===false) $old_value = '';
    if ($new_value==='' || $new_value===null || $new_value===false) $new_value = '';

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$local_name] = true;
      $this->myAttributes['value']   = $new_value;
    }

    // The user can enter any text in a input:text box. So, any value is white listed.
    $theWhiteListValue[$local_name] = $new_value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:text must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['value'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:password.
 */
class SET_HtmlFormControlPassword extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myAttributes['set_clean'] = 'SET_HtmlClean::PruneWhitespace';
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'maxlength':
    // case 'name':
    case 'size':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'set_prefix':
    case 'set_postfix':
    case 'set_clean':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'password' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        case 'size':
          if (isset($this->myAttributes['maxlength'])) $value = min( $value, $this->myAttributes['maxlength'] );
          $ret .= SET_Html::GenerateAttribute( $name, $value );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if ($this->myAttributes['set_clean']) $new_value = call_user_func( $this->myAttributes['set_clean'], $theSubmittedValue[$submit_name] );
    else                                  $new_value = $theSubmittedValue[$submit_name];

    // Normalize old (original) value and new (submitted) value.
    $old_value = (isset($this->myAttributes['value'])) ? $this->myAttributes['value'] : null;
    if ($old_value==='' || $old_value===null || $old_value===false) $old_value = '';
    if ($new_value==='' || $new_value===null || $new_value===false) $new_value = '';

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$local_name] = true;
      $this->myAttributes['value']   = $new_value;
    }

    // The user can enter any text in a input:password box. So, any value is white listed.
    $theWhiteListValue[$local_name] = $new_value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:password must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['value'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:hidden.
 */
class SET_HtmlFormControlHidden extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    // case 'name':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'set_prefix':
    case 'set_postfix':
    case 'set_clean':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'hidden' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if (isset($this->myAttributes['set_clean'])) $new_value = call_user_func( $this->myAttributes['set_clean'], $theSubmittedValue[$submit_name] );
    else                                         $new_value = $theSubmittedValue[$submit_name];

    // Normalize old (original) value and new (submitted) value.
    $old_value = (isset($this->myAttributes['value'])) ? $this->myAttributes['value'] : null;
    if ($old_value==='' || $old_value===null || $old_value===false) $old_value = '';
    if ($new_value==='' || $new_value===null || $new_value===false) $new_value = '';

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$local_name] = true;
      $this->myAttributes['value']   = $new_value;
    }

    // Any text can be in a input:hidden box. So, any value is white listed.
    $theWhiteListValue[$local_name] = $new_value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:hidden must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['value'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:hidden, hoever, the submitted value is never loaded.
 */
class SET_HtmlFormControlInvisable extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'maxlength':
    // case 'name':
    case 'size':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'set_prefix':
    case 'set_postfix':
    case 'set_clean':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'hidden' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Note: by definition the value of a input:invisible form control will not be changed, whatever is submitted.
    $local_name = $this->myAttributes['name'];
    $value      = $this->myAttributes['value'];

    $theWhiteListValue[$local_name] = $value;

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $this->myAttributes[$local_name];

      // The value of a input:hidden must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['value'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:radio.
 *
 * @todo Add attribute for label.
 */
class SET_HtmlFormControlRadio extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'checked':
    //case 'name':
    //case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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

      // H2O Attributes xxx
    case 'set_clean':
    case 'set_obfuscator':
    case 'set_postfix':
    case 'set_prefix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'radio' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if ((string)$theSubmittedValue[$submit_name]===(string)$this->myAttributes['value'])
    {
      if (empty($this->myAttributes['checked'])) $theChangedInputs[$local_name] = true;
      $this->myAttributes['checked']  = true;
      $theWhiteListValue[$local_name] = $this->myAttributes['value'];

      // Set the submitted value to be used method GetSubmittedValue.
      $this->myAttributes['set_submitted_value'] =  $theWhiteListValue[$local_name];
    }
    else
    {
      if (!empty($this->myAttributes['checked'])) $theChangedInputs[$local_name] = true;
      $this->myAttributes['checked'] = false;

      if (!array_key_exists( $local_name, $theWhiteListValue )) $theWhiteListValue[$local_name] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:checkbox must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when empty? */
      $this->myAttributes['checked'] = !empty($value);
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['checked']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:checkbox.
 *
 * @todo Add attribute for label.
 */
class SET_HtmlFormControlCheckbox extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'checked':
    // case 'name':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'onkeydown':
    case 'onkeypress':
    case 'onkeyup':
    case 'onmousedown':
    case 'onmousemove':
    case 'onmouseout':
    case 'onmouseover':
    case 'onmouseup':

      // Common style attribute.
    case 'style':

      // H2O Attributes
    case 'set_clean':
    case 'set_obfuscator':
    case 'set_postfix':
    case 'set_prefix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'checkbox' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if (empty($this->myAttributes['checked'])!==empty($theSubmittedValue[$submit_name]))
    {
      $theChangedInputs[$local_name] = true;
    }

    /** @todo Decide whether to test submited value is white listed, i.e. $this->myAttributes['value'] (or 'on'
     *  if $this->myAttributes['value'] is null) or null.
     */
    if (!empty($theSubmittedValue[$submit_name]))
    {
      $this->myAttributes['checked']  = true;
      $this->myAttributes['value']    = $theSubmittedValue[$submit_name];
      $theWhiteListValue[$local_name] = true;
    }
    else
    {
      $this->myAttributes['checked']  = false;
      $this->myAttributes['value']    = '';
      $theWhiteListValue[$local_name] = false;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $this->myAttributes['checked'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:checkbox must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when empty? */
      $this->myAttributes['checked'] = !empty($value);
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['checked']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Base class for form controls submit, reset, and button
 */
class SET_HtmlFormControlPushMe extends SET_HtmlFormControlSimple
{
  /** The type of this button. Valid values are:
   *  \li submit
   *  \li reset
   *  \li button
   */
  protected $myButtonType;

  //--------------------------------------------------------------------------------------------------------------------
  /** Creates a SET_HtmlFormControlPushMe object.
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    // case 'name':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'set_prefix':
    case 'set_postfix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', $this->myButtonType );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          // For buttons we use local names. It is the task of the developer to ensure the local names of buttons are unique.
          $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
          $local_name  = $this->myAttributes['name'];
          $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if ($theSubmittedValue[$submit_name]===$this->myAttributes['value'])
    {
      // We don't register buttons as a changed input, otherwise every submited form will always have changed inputs.
      // $theChangedInputs[$local_name] = true;

      $theWhiteListValue[$local_name] = $this->myAttributes['value'];
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $this->myAttributes['value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    // We don't set the value of a button via SET_HtmlForm::SetValues() method. So, nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:submit.
 */
class SET_HtmlFormControlSubmit extends SET_HtmlFormControlPushMe
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myButtonType = 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:reset.
 */
class SET_HtmlFormControlReset extends SET_HtmlFormControlPushMe
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myButtonType = 'reset';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type input:button.
 */
class SET_HtmlFormControlButton extends SET_HtmlFormControlPushMe
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myButtonType = 'button';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type file.
 */
class SET_HtmlFormControlFile extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    // case 'name':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
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
    case 'set_prefix':
    case 'set_postfix':
    case 'set_clean':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= '<input';

    $ret .= SET_Html::GenerateAttribute( 'type', 'file' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if ($_FILES[$submit_name]['error']===0)
    {
      $theChangedInputs[$local_name]  = true;
      $theWhiteListValue[$local_name] = $_FILES[$submit_name];
      $this->myAttributes['value']    = $_FILES[$submit_name];
    }
    else
    {
      $theWhiteListValue[$local_name] = false;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/** @brief Class for form controls of type image.
 */
class SET_HtmlFormControlImage extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'alt':
    // case 'name':
    // case 'type':
    case 'value':

      // Advanced attributes.
    case 'accept':
    case 'accesskey':
    case 'disabled':
    case 'ismap':
    case 'onblur':
    case 'onchange':
    case 'onfocus':
    case 'onselect':
    case 'readonly':
    case 'src':
    case 'tabindex':
    case 'usemap':

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
    case 'set_prefix':
    case 'set_postfix':
    case 'set_clean':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName  )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= $this->GeneratePrefixLabel();
    $ret .= "<input";

    $ret .= SET_Html::GenerateAttribute( 'type', 'image' );

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $global_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }

    $ret .= '/>';
    $ret .= $this->GeneratePostfixLabel();
    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix'];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    /** @todo Implement LoadSumittedValuesBase for control type image.
     */
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlSpan extends SET_HtmlFormControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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
    case 'set_html':
    case 'set_prefix':
    case 'set_postfix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<span';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        // Nothing to do
        break;

      default:
        $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">";

    if (!empty($this->myAttributes['set_html'])) $ret .= $this->myAttributes['set_html'];
    $ret .= "</span>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function ValidateBase( &$theInvalidFormControls )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlLink extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes
    case 'href':

      // Advanced attributes
    case 'accesskey':
    case 'charset':
    case 'coords':
    case 'hreflang':
    case 'onblur':
    case 'onfocus':
    case 'rel':
    case 'rev':
    case 'shape':
    case 'tabindex':
    case 'type':

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
    case 'set_html':
    case 'set_prefix':
    case 'set_postfix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<a';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        // Nothing to do
        break;

      default:
        $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">";

    if (!empty($this->myAttributes['set_html'])) $ret .= $this->myAttributes['set_html'];
    $ret .= "</a>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function ValidateBase( &$theInvalidFormControls )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlDiv extends SET_HtmlFormControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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
    case 'set_html':
    case 'set_prefix':
    case 'set_postfix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<div';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        // Nothing to do
        break;

      default:
        $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">\n";

    if (!empty($this->myAttributes['set_html'])) $ret .= $this->myAttributes['set_html']."\n";
    $ret .= "</div>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function ValidateBase( &$theInvalidFormControls )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlConstant extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
    case 'set_value':
      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $local_name = $this->myAttributes['name'];

    $theWhiteListValue[$local_name] = $this->myAttributes['set_value'];

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $this->myAttributes['set_value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function ValidateBase( &$theInvalidFormControls )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:hidden must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
      }

      /** @todo unset when false or ''? */
      $this->myAttributes['set_value'] = (string)$value;
    }
    else
    {
      // No value specified for this form control: unset the value of this form control.
      unset($this->myAttributes['set_value']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlTextArea extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myAttributes['set_clean'] = 'SET_HtmlClean::TrimWhitespace';
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
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<textarea';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">";

    if (!empty($this->myAttributes['set_text'])) $ret .= SET_Html::Txt2Html( $this->myAttributes['set_text'] );
    $ret .= "</textarea>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if (isset($this->myAttributes['set_clean'])) $new_value = call_user_func( $this->myAttributes['set_clean'], $theSubmittedValue[$submit_name] );
    else                                         $new_value = $theSubmittedValue[$submit_name];

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
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if (isset($theValues[$local_name]))
    {
      $value = $theValues[$local_name];

      // The value of a input:hidden must be a scalar.
      if (!is_scalar($value))
      {
        SET_Html::Error( "Illegal value '%s' for form control '%s'.", $value, $local_name );
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
class SET_HtlmFormControlSelect extends SET_HtmlFormControlSimple
{
  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** @todo Implement 'multiple'.
   */
  public function Generate( $theParentName )
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<select';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
        case 'name':
          $submit_name = $this->GetSubmitName( $theParentName );
          $ret .= SET_Html::GenerateAttribute( $name, $submit_name );
          break;

        default:
          $ret .= SET_Html::GenerateAttribute( $name, $value );
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
        $code = ($map_obfuscator) ? $map_obfuscator->Encode( $id ) : $id;

        //
        $ret .= "<option value='$code'";

        if ($this->myAttributes['set_value']===$id) $ret .= " selected='selected'";

        if ($map_disabled && !empty($option[$map_disabled])) $ret .= " disabled='disabled'";

        $ret .= ">";
        $ret .= SET_Html::Txt2Html( $option[$map_label] );
        $ret .= "</option>\n";
      }
    }

    $ret .= "</select>";

    if (isset($this->myAttributes['set_postfix'])) $ret .= $this->myAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

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
            $code = ($map_obfuscator) ? $map_obfuscator->Encode( $id ) : $id;

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
  public function SetValuesBase( &$theValues )
  {
    /** @todo check on type and value is in list of options. */
    $local_name = $this->myAttributes['name'];
    $this->myAttributes['set_value'] = $theValues[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtlmFormControlRadios extends SET_HtmlFormControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
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
        $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">\n";

    if (is_array($this->myAttributes['set_options']))
    {
      $map_key        = $this->myAttributes['set_map_key'];
      $map_label      = $this->myAttributes['set_map_label'];
      $map_disabled   = (isset($this->myAttributes['set_map_disabled']))   ? $this->myAttributes['set_map_disabled']   : null;
      $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

      $submit_name = $this->GetSubmitName( $theParentName );
      foreach( $this->myAttributes['set_options'] as $option )
      {
        $code = ($map_obfuscator) ? $map_obfuscator->Encode( $option[$map_key] ) : $option[$map_key];

        $for_id = SET_Html::GetAutoId();

        $input = "<input type='radio' name='$submit_name' value='$code' id='$for_id'";

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$option[$map_key])
        {
          $input .= " checked='checked'";
        }

        if ($map_disabled && !empty($option[$map_disabled])) $input .= " disabled='disabled'";

        $input .= "/>";

        $label  = (isset($this->myAttributes['set_label_prefix'])) ? $this->myAttributes['set_label_prefix'] : '';
        $label .= "<label for='$for_id'>";
        $label .= SET_Html::Txt2Html( $option[$map_label] );
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
  protected function ValidateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach( $this->myValidators as $validator )
    {
      $valid = $validator->Validate( $this );
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
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

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
        $code = ($map_obfuscator) ? $map_obfuscator->Encode( $id ) : $id;

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
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    $this->myAttributes['set_value'] = $theValues[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
/**
   @todo Implement disabled hard (can not be changed via javascript) and disabled sort (can be changed via javascript).
 */
class SET_HtlmFormControlCheckboxes extends SET_HtmlFormControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // A SET_HtlmFormControlCheckboxes must always have a name.
    $local_name = $this->myAttributes['name'];
    if ($local_name===false) SET_Html::Error( 'Name is emtpy' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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
    case 'onkeydown':
    case 'onkeypress':
    case 'onkeyup':
    case 'onmousedown':
    case 'onmousemove':
    case 'onmouseout':
    case 'onmouseover':
    case 'onmouseup':

      // Common style attribute.
    case 'style':

      // H2O Attributes
    case 'set_label_postfix':
    case 'set_label_prefix':
    case 'set_map_checked':
    case 'set_map_disabled':
    case 'set_map_id':
    case 'set_map_key':
    case 'set_map_label':
    case 'set_map_obfuscator':
    case 'set_options':
    case 'set_postfix':
    case 'set_prefix':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $submit_name = $this->GetSubmitName( $theParentName );

    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<div';

    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        // Nothing to do
        break;

      default:
        $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">\n";

    if (is_array($this->myAttributes['set_options']))
    {
      $map_id         = $this->myAttributes['set_map_id'];
      $map_key        = $this->myAttributes['set_map_key'];
      $map_label      = $this->myAttributes['set_map_label'];
      $map_checked    = $this->myAttributes['set_map_checked'];
      $map_disabled   = (isset($this->myAttributes['set_map_disabled']))   ? $this->myAttributes['set_map_disabled']   : null;
      $map_obfuscator = (isset($this->myAttributes['set_map_obfuscator'])) ? $this->myAttributes['set_map_obfuscator'] : null;

      foreach( $this->myAttributes['set_options'] as $option )
      {
        $code = ($map_obfuscator) ? $map_obfuscator->Encode( $option[$map_key] ) : $option[$map_key];

        if ($map_id && isset($option[$map_id])) $id = $option[$map_id];
        else                                    $id = SET_Html::GetAutoId();

        $input = "<input type='checkbox'";

        $input .= SET_Html::GenerateAttribute( 'name', "${submit_name}[$code]" );

        $input .= SET_Html::GenerateAttribute( 'id', $id );

        if ($map_checked) $input .= SET_Html::GenerateAttribute( 'checked', $option[$map_checked] );

        if ($map_disabled) $input .= SET_Html::GenerateAttribute( 'disabled', $option[$map_checked] );

        $input .= "/>";

        $label  = $this->myAttributes['set_label_prefix'];
        $label .= "<label for='$id'>";
        $label .= SET_Html::Txt2Html( $option[$map_label] );
        $label .= "</label>";
        $label .= $this->myAttributes['set_label_postfix'];

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
  protected function ValidateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach( $this->myValidators as $validator )
    {
      $valid = $validator->Validate( $this );
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
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if ($local_name!==false) $values = &$theValues[$local_name];
    else                     $values = &$theValues;

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
  protected function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

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
        $code = ($map_obfuscator) ? $map_obfuscator->Encode( $id ) : $id;

        // Get the orginal value (i.e. the option is checked or not).
        $value = (isset($option[$map_checked])) ? $option[$map_checked] : false;

        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$submit_name][$code])) ? $theSubmittedValue[$submit_name][$code] : false;

        // If the orginal value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$local_name][$id] = true;

        // Set the white listed value.
        $theWhiteListValue[$local_name][$id]                 = !empty($submitted);
        $this->myAttributes['set_options'][$i][$map_checked] = !empty($submitted);
      }
    }
    else
    {
      // No checkboxes have been checked.
      $theWhiteListValue[$local_name] = array();
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$local_name];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFormControlComplex extends SET_HtmlFormControl
{
  /** The child HTML form controls
   */
  protected $myControls = array();

  //--------------------------------------------------------------------------------------------------------------------
  /** A factory for creating form control objects.
    */
  public function CreateFormControl( $theType, $theName )
  {
    switch ($theType)
    {
    case 'text':
      $type = 'SET_HtmlFormControlText';
      break;

    case 'password':
      $type = 'SET_HtmlFormControlPassword';
      break;

    case 'checkbox':
      $type = 'SET_HtmlFormControlCheckbox';
      break;

    case 'radio':
      $type = 'SET_HtmlFormControlRadio';
      break;

    case 'submit':
      $type = 'SET_HtmlFormControlSubmit';
      break;

    case 'image':
      $type = 'SET_HtmlFormControlImage';
      break;

    case 'reset':
      $type = 'SET_HtmlFormControlReset';
      break;

    case 'button':
      $type = 'SET_HtmlFormControlButton';
      break;

    case 'hidden':
      $type = 'SET_HtmlFormControlHidden';
      break;

    case 'file':
      $type = 'SET_HtmlFormControlFile';
      break;

    case 'invisable':
      $type = 'SET_HtmlFormControlInvisable';
      break;

    case 'textarea':
      $type = 'SET_HtmlFormControlTextArea';
      break;

    case 'complex':
      $type = 'SET_HtmlFormControlComplex';
      break;

    case 'select':
      $type = 'SET_HtlmFormControlSelect';
      break;

    case 'span':
      $type = 'SET_HtmlFormControlSpan';
      break;

    case 'div':
      $type = 'SET_HtmlFormControlDiv';
      break;

    case 'a':
      $type = 'SET_HtmlFormControlLink';
      break;

    case 'constant':
      $type = 'SET_HtmlFormControlConstant';
      break;

    case 'radios':
      $type = 'SET_HtlmFormControlRadios';
      break;

    case 'checkboxes':
      $type = 'SET_HtlmFormControlCheckboxes';
      break;

    default:
      $type = $theType;
    }

    $tmp = new $type( $theName );
    $this->myControls[] = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
    case 'set_obfuscator':
      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $submit_name = $this->GetSubmitName( $theParentName );

    $ret = false;
    foreach( $this->myControls as $control )
    {
      $ret .= $control->Generate( $submit_name );
      $ret .= "\n";
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function GetErrorMessages( $theRecursiveFlag=false )
  {
    $ret = array();
    if ($theRecursiveFlag)
    {
      foreach( $this->myControls as $control )
      {
        $tmp = $control->GetErrorMessages( true );
        if (is_array($tmp))
        {
          $ret = array_merge( $ret, $tmp );
        }
      }
    }

    if (is_array($this->myAttributes['set_errmsg']))
    {
      $ret = array_merge( $ret, $this->myAttributes['set_errmsg'] );
    }


    if (empty($ret)) $ret = false;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function LoadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $local_name  = $this->myAttributes['name'];
    $submit_name = ($obfuscator) ? $obfuscator->Encode( $local_name ) : $local_name;

    if ($local_name===false)
    {
      $tmp1 = &$theSubmittedValue;
      $tmp2 = &$theWhiteListValue;
      $tmp3 = &$theChangedInputs;
    }
    else
    {
      $tmp1 = &$theSubmittedValue[$submit_name];
      $tmp2 = &$theWhiteListValue[$local_name];
      $tmp3 = &$theChangedInputs[$local_name];
    }

    foreach( $this->myControls as $control )
    {
      $control->LoadSubmittedValuesBase( $tmp1, $tmp2, $tmp3 );
    }

    if ($local_name!==false)
    {
      if (empty($theWhiteListValue[$local_name])) unset( $theWhiteListValue[$local_name] );
      if (empty($theChangedInputs[$local_name]))  unset( $theChangedInputs[$local_name] );
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $tmp2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValuesBase( &$theValues )
  {
    $local_name = $this->myAttributes['name'];
    if ($local_name!==false) $values = &$theValues[$local_name];
    else                     $values = &$theValues;

    foreach( $this->myControls as $control )
    {
      $control->SetValuesBase( $values );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function ValidateSelf( &$theInvalidFormControls )
  {
    $local_name = $this->myAttributes['name'];
    $valid      = true;

    foreach( $this->myValidators as $validator )
    {
      $valid = $validator->Validate( $this );
      if ($valid!==true)
      {
        if ($local_name!==false) $theInvalidFormControls[$local_name] = true;
        else                     $theInvalidFormControls              = true;

        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function ValidateBase( &$theInvalidFormControls )
  {
    $tmp = array();

    // First, validate all child form controls.
    foreach( $this->myControls as $control )
    {
      $control->ValidateBase( $tmp );
    }

    $local_name = $this->myAttributes['name'];

    if (empty($tmp))
    {
      // All the individual child form controls are valid. Validate the child form controls as a whole.
      $valid = $this->ValidateSelf( $theInvalidFormControls );
    }
    else
    {
      // One or more input values are invalid. Append the names of the invalid form controls to $theInvalidFormControls.
      $local_name = $this->myAttributes['name'];
      if ($local_name!==false)
      {
        $theInvalidFormControls[$local_name] = $tmp;
      }
      else
      {
        foreach( $tmp as $name => $t )
        {
          $theInvalidFormControls[$name] = $t;
        }
      }

      $valid = false;
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
      exists the first found form control is returned. If no form control with @a $thePath exists @c null is
      returned.
      @param  $thePath The path of the searched form control.
      @return A form control with path $thePath or @c null of no form control has been found.

      @sa GetFormControlByPath.
   */
  public function FindFormControlByPath( $thePath )
  {
    if ($thePath===null || $thePath===false || $thePath==='' || $thePath==='/')
    {
      return null;
    }

    $parts = preg_split( '/\/+/', $thePath );
    foreach( $this->myControls as $control )
    {
      if ($control->myAttributes['name']===$parts[0])
      {
        if (sizeof($parts)===1)
        {
          return $control;
        }
        else
        {
          array_shift( $parts );
          return $control->FindFormControlByPathBase( implode( '/', $parts ) );
        }
      }
      else
      {
        $tmp = $control->FindFormControlByPathBase( $thePath );
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
      exists the first found form control is returned. If no form control with @a $thePath exists an exception will
      be thrown.
      @param  $thePath The path of the searched form control.
      @return A form control with path $thePath.

      @sa FindFormControlByPath.
   */
  public function GetFormControlByPath( $thePath )
  {
    $control = $this->FindFormControlByPath( $thePath );

    if ($control===null) SET_Html::Error( "No form control with path '%s' exists.", $thePath );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with name @a $theName. If more than one form control with name @a $theName
      exists the first found form control is returned. If no form control with @a $theName exists @c null is
      returned.
      @param  $theName The name of the searched form control.
      @return A form control with name $theName or @c null of no form control has been found.

      @sa GetFormControlByName.
   */
  public function FindFormControlByName( $theName )
  {
    foreach( $this->myControls as $control )
    {
      if ($control->myAttributes['name']===$theName) return $control;

      if (is_a($control,'SET_HtmlFormControlComplex'))
      {
        $tmp = $control->FindFormControlByName( $theName );
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with name @a $theName. If more than one form control with name @a $theName
      exists the first found form control is returned. If no form control with @a $theName exists an exception will
      be thrown.
      @param  $theName The name of the searched form control.
      @return A form control with name $theName.

      @sa FindFormControlByName.
   */
  public function GetFormControlByName( $theName )
  {
    $control = $this->FindFormControlByName( $theName );

    if ($control===null) SET_Html::Error( "No form control with name '%s' found.", $theName );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlFieldSet extends SET_HtmlFormControlComplex
{
  protected $myLegend;

  //--------------------------------------------------------------------------------------------------------------------
  public function __construct( $theName=false )
  {
    parent::__construct( $theName );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
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

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function CreateLegend( $theType='legend' )
  {
    switch ($theType)
    {
    case 'legend':
      $tmp = new SET_HtmlLegend();
      break;

    default:
      $tmp = new $theType();
    }

    $this->myLegend = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateOpenTag()
  {
    $ret = '<fieldset';
    foreach( $this->myAttributes as $name => $value )
    {
      switch ($name)
      {
      case 'name':
        // Element fieldset does not have a attribute name. So, nothing to do.
        break;

      default:
        $ret .= SET_Html::GenerateAttribute( $name, $value );
      }
    }
    $ret .= ">\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateLegend()
  {
    if ($this->myLegend) $ret = $this->myLegend->Generate();
    else                 $ret = false;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateCloseTag()
  {
    $ret = "</fieldset>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate( $theParentName )
  {
    $ret  = $this->GenerateOpenTag();

    $ret .= $this->GenerateLegend();

    $ret .= parent::Generate( $theParentName );

    $ret .= $this->GenerateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}


//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlLegend
{
  protected $myAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  public function __construct()
  {
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Helper function for SET_HtmlLegend::SetAttribute. Sets the value of attribute with name @a $theName of this form to
      @a $theValue. If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
   */
  protected function SetAttributeBase( $theName, $theValue )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myAttributes[$theName] );
    }
    else
    {
      if ($theName==='class' && isset($this->myAttributes[$theName]))
      {
        $this->myAttributes[$theName] .= ' ';
        $this->myAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Advanced attributes.
    case 'accesskey':

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

     // H2O attributes.
    case 'set_inline':

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate()
  {
    $ret .= "<legend";

    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= SET_Html::GenerateAttribute( $name, $value );
    }

    $ret .= '>';

    $ret .= $this->myAttributes['set_inline'];

    $ret .= "</legend>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
class SET_HtmlForm
{
  /** The attributes of this form.
   */
  protected $myAttributes = array();

  /** The field sets of this form.
   */
  protected $myFieldSets = array();

  /** After a call to SET_HtmlForm::LoadSubmittedValues holds the names of the form controls of which the value has
      changed.
   */
  protected $myChangedControls = array();

  /** After a call to SET_HtmlForm::LoadSubmittedValues holds the white-listed submitted values.
   */
  protected $myValues = array();

  /** After a call to SET_HtmlForm::Validate holds the names of the form controls which have valid one or more
      validation tests.
   */
  protected $myInvalidControls = array();


  //--------------------------------------------------------------------------------------------------------------------
  /** Object constructor.
   */
  public function __construct()
  {
    $this->myAttributes['action'] = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
    $this->myAttributes['method'] = 'post';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Creates a fieldset of type @a $theType and with name @a $theName and appends this fieldset to the list of field
      sets of this form.
      @param  $theType The class name of the fieldset which must be derived from class SET_HtmlFieldSet. The following
                       alias are implemented:
                       - fieldset: class SET_HtmlFieldSet
      @param  $theName The name (which might be empty) of the fieldset.
      @return The created fieldset.
   */
  public function CreateFieldSet( $theType='fieldset', $theName=false )
  {
    switch ($theType)
    {
    case 'fieldset':
      $type = 'SET_HtmlFieldSet';
      break;

    default:
      $type = $theType;
    }

    $tmp = new $type( $theName );
    $this->myFieldSets[] = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Helper function for SET_HtmlForm::SetAttribute. Sets the value of attribute with name @a $theName of this form to
      @a $theValue. If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
   */
  protected function SetAttributeBase( $theName, $theValue )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myAttributes[$theName] );
    }
    else
    {
      if ($theName==='class' && isset($this->myAttributes[$theName]))
      {
        $this->myAttributes[$theName] .= ' ';
        $this->myAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Sets the value of attribute with name @a $theName of this form to @a $theValue. If @a $theValue is @c null,
      @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
      @todo Document @a theExtendedFlag
   */
  public function SetAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Basic attributes.
    case 'action':
    case 'method':

      // Advanced attributes.
    case 'accept':
    case 'accept-charsets':
    case 'enctype':
    case 'onreset':

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

      $this->SetAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->SetAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::Error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function LoadSubmittedValues()
  {
    switch ($this->myAttributes['method'])
    {
    case 'post':
      $values = &$_POST;
      break;

    case 'get':
      $values = &$_GET;
      break;

    default:
      SET_Html::Error( "Unknown method '%s'.", $this->myAttributes['method'] );
    }

    foreach( $this->myFieldSets as $fieldset )
    {
      $fieldset->LoadSubmittedValuesBase( $values, $this->myValues, $this->myChangedControls );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Validates all form controls of this form against all their installed validation checks.
      @return @c true if and only if all form controls fulfill all their validation checks. Otherwise, returns @c false.
      @note This method should only be involked after method SET_HtmlForm::LoadSubmittedValues() has been involked.
   */
  public function Validate()
  {
    foreach( $this->myFieldSets as $fieldset )
    {
      $fieldset->ValidateBase( $this->myInvalidControls );
    }

    return (empty($this->myInvalidControls));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @c true if and only if the value of one or more submitted form controls have changed. Otherwise returns
              @c false.
      @note This method should only be involked after method SET_HtmlForm::LoadSubmittedValues() has been involked.
   */
  public function HaveChangedInputs()
  {
    return !empty($this->myChangedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateOpenTag()
  {
    $ret = '<form';
    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= SET_Html::GenerateAttribute( $name, $value );
    }
    $ret .= ">\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateBody()
  {
    $ret = false;
    foreach( $this->myFieldSets as $fieldset )
    {
      $ret .= $fieldset->Generate( false );
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function GenerateCloseTag()
  {
    $ret = "</form>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function Generate()
  {
    $ret = $this->GenerateOpenTag();

    $ret .= $this->GenerateBody();

    $ret .= $this->GenerateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the submitted values of all form controls.
      @return A nested array of form control names (keys are form control names and (for complex form controls) values
              are arrays or (for simple form controls) the submitted value).
      @note This method should only be involked after method SET_HtmlForm::LoadSubmittedValues() has been involked.
   */
  public function GetValues()
  {
    return $this->myValues;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function SetValues( $theValues )
  {
    foreach( $this->myFieldSets as $fieldset )
    {
      $fieldset->SetValuesBase( $theValues );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns all form control names of which the value has been changed.
      @return A nested array of form control names (keys are form control names and (for complex form controls) values
              are arrays or (for simple form controls) @c true).
      @note This method should only be involked after method SET_HtmlForm::LoadSubmittedValues() has been involked.
   */
  public function GetChangedControls()
  {
    return $this->myChangedControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns all form controls which failed one or more validation tests.
      @return A nested array of form control names (keys are form control names and (for complex form controls) values
              are arrays or (for simple form controls) @c true).
      @note This method should only be involked after method SET_HtmlForm::Validate() has been involked.
   */
  public function GetInvalidControls()
  {
    return $this->myInvalidControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @c true if @a $theArray has one or more scalars. Otherwise, returns @c false.
   */
  static public function HasScalars( $theArray )
  {
    $ret = false;
    foreach( $theArray as $tmp )
    {
      if (is_scalar($tmp))
      {
        $ret = true;
        break;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @c true if the element (of type submit or image) has been submitted.
   */
  public function IsSubmitted( $theName )
  {
    /** @todo check value is whitelisted. */
    switch ($this->myAttributes['method'])
    {
    case 'post':
      if (isset($_POST[$theName])) return true;
      break;

    case 'get':
      if (isset($_GET[$theName])) return true;
      break;

    default:
      SET_Html::Error( "Unknown method '%s'.", $this->myAttributes['method'] );
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
      exists the first found form control is returned. If no form control with @a $thePath exists @c null is
      returned.
      @param  $thePath The path of the searched form control.
      @return A form control with path $thePath or @c null of no form control has been found.

      @sa GetFormControlByPath.
   */
  public function FindFormControlByPath( $thePath )
  {
    if ($thePath===null || $thePath===false || $thePath==='' || $thePath==='/')
    {
      return null;
    }

    // $thePath must start with a leading slash.
    if (substr( $thePath, 0, 1 )!=='/') return null;

    $parts = preg_split( '/\/+/', $thePath );
    foreach( $this->myFieldSets as $control )
    {
      if ($control->GetLocalName()===$parts[0])
      {
        if (sizeof($parts)===1)
        {
          return $control;
        }
        else
        {
          array_shift( $parts );
          return $control->FindFormControlByPathBase( implode( '/', $parts ) );
        }
      }
      else
      {
        $tmp = $control->FindFormControlByPathBase( $thePath );
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
      exists the first found form control is returned. If no form control with @a $thePath exists an exception will
      be thrown.
      @param  $thePath The path of the searched form control.
      @return A form control with path $thePath.

      @sa FindFormControlByPath.
   */
  public function GetFormControlByPath( $thePath )
  {
    $control = $this->FindFormControlByPath( $thePath );

    if ($control===null) SET_Html::Error( "No form control with path '%s' exists.", $thePath );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with name @a $theName. If more than one form control with name @a $theName
      exists the first found form control is returned. If no form control with @a $theName exists @c null is
      returned.
      @param  $theName The name of the searched form control.
      @return A form control with name $theName or @c null of no form control has been found.

      @sa GetFormControlByName.
   */
  public function FindFormControlByName( $theName )
  {
    foreach( $this->myFieldSets as $fieldset )
    {
      if ($fieldset->GetLocalName()===$theName) return $fieldset;

      $control = $fieldset->FindFormControlByName( $theName );
      if ($control) return $control;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with name @a $theName. If more than one form control with name @a $theName
      exists the first found form control is returned. If no form control with @a $theName exists an exception will
      be thrown.
      @param  $theName The name of the searched form control.
      @return A form control with name $theName.

      @sa FindFormControlByName.
   */
  public function GetFormControlByName( $theName )
  {
    $control = $this->FindFormControlByName( $theName );

    if ($control===null) SET_Html::Error( sprintf( "No form control with name '%s' found.", $theName ) );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
