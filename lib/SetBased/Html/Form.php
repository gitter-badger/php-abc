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
namespace SetBased\Html;
use SetBased\Html\Form;

//----------------------------------------------------------------------------------------------------------------------
class Form
{
  /** The attributes of this form.
   */
  protected $myAttributes = array();

  /** The field sets of this form.
   */
  protected $myFieldSets = array();

  /** After a call to SET_HtmlForm::loadSubmittedValues holds the names of the form controls of which the value has
      changed.
   */
  protected $myChangedControls = array();

  /** After a call to SET_HtmlForm::loadSubmittedValues holds the white-listed submitted values.
   */
  protected $myValues = array();

  /** After a call to SET_HtmlForm::validate holds the names of the form controls which have valid one or more
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
  public function createFieldSet( $theType='fieldset', $theName=false )
  {
    switch ($theType)
    {
    case 'fieldset':
      $type = '\SetBased\Html\Form\FieldSet';
      break;

    default:
      $type = $theType;
    }

    $tmp = new $type( $theName );
    $this->myFieldSets[] = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Helper function for SET_HtmlForm::setAttribute. Sets the value of attribute with name @a $theName of this form to
      @a $theValue. If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
   */
  protected function setAttributeBase( $theName, $theValue )
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
  public function setAttribute( $theName, $theValue, $theExtendedFlag=false )
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

      $this->setAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->setAttributeBase( $theName, $theValue );
      }
      else
      {
        SET_Html::error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function loadSubmittedValues()
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
      SET_Html::error( "Unknown method '%s'.", $this->myAttributes['method'] );
    }

    foreach( $this->myFieldSets as $fieldset )
    {
      $fieldset->loadSubmittedValuesBase( $values, $this->myValues, $this->myChangedControls );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Validates all form controls of this form against all their installed validation checks.
      @return @c true if and only if all form controls fulfill all their validation checks. Otherwise, returns @c false.
      @note This method should only be involked after method SET_HtmlForm::loadSubmittedValues() has been involked.
   */
  public function validate()
  {
    foreach( $this->myFieldSets as $fieldset )
    {
      $fieldset->validateBase( $this->myInvalidControls );
    }

    return (empty($this->myInvalidControls));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @c true if and only if the value of one or more submitted form controls have changed. Otherwise returns
              @c false.
      @note This method should only be involked after method SET_HtmlForm::loadSubmittedValues() has been involked.
   */
  public function haveChangedInputs()
  {
    return !empty($this->myChangedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateOpenTag()
  {
    $ret = '<form';
    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= SET_Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateBody()
  {
    $ret = false;
    foreach( $this->myFieldSets as $fieldset )
    {
      $ret .= $fieldset->generate( false );
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateCloseTag()
  {
    $ret = "</form>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate()
  {
    $ret = $this->generateOpenTag();

    $ret .= $this->generateBody();

    $ret .= $this->generateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the submitted values of all form controls.
      @return A nested array of form control names (keys are form control names and (for complex form controls) values
              are arrays or (for simple form controls) the submitted value).
      @note This method should only be involked after method SET_HtmlForm::loadSubmittedValues() has been involked.
   */
  public function getValues()
  {
    return $this->myValues;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValues( $theValues )
  {
    foreach( $this->myFieldSets as $fieldset )
    {
      $fieldset->setValuesBase( $theValues );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns all form control names of which the value has been changed.
      @return A nested array of form control names (keys are form control names and (for complex form controls) values
              are arrays or (for simple form controls) @c true).
      @note This method should only be involked after method SET_HtmlForm::loadSubmittedValues() has been involked.
   */
  public function getChangedControls()
  {
    return $this->myChangedControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns all form controls which failed one or more validation tests.
      @return A nested array of form control names (keys are form control names and (for complex form controls) values
              are arrays or (for simple form controls) @c true).
      @note This method should only be involked after method SET_HtmlForm::validate() has been involked.
   */
  public function getInvalidControls()
  {
    return $this->myInvalidControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns @c true if @a $theArray has one or more scalars. Otherwise, returns @c false.
   */
  static public function hasScalars( $theArray )
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
  public function isSubmitted( $theName )
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
      SET_Html::error( "Unknown method '%s'.", $this->myAttributes['method'] );
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
  public function findFormControlByPath( $thePath )
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
      if ($control->getLocalName()===$parts[0])
      {
        if (sizeof($parts)===1)
        {
          return $control;
        }
        else
        {
          array_shift( $parts );
          return $control->findFormControlByPathBase( implode( '/', $parts ) );
        }
      }
      else
      {
        $tmp = $control->findFormControlByPathBase( $thePath );
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
  public function getFormControlByPath( $thePath )
  {
    $control = $this->findFormControlByPath( $thePath );

    if ($control===null) SET_Html::error( "No form control with path '%s' exists.", $thePath );

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
  public function findFormControlByName( $theName )
  {
    foreach( $this->myFieldSets as $fieldset )
    {
      if ($fieldset->getLocalName()===$theName) return $fieldset;

      $control = $fieldset->findFormControlByName( $theName );
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
  public function getFormControlByName( $theName )
  {
    $control = $this->findFormControlByName( $theName );

    if ($control===null) SET_Html::error( sprintf( "No form control with name '%s' found.", $theName ) );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
