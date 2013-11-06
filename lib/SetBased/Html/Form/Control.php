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
/** @brief Abstract class for objects for generation HTML code for form controls.
 */
abstract class Control
{
  /** The (local) name of this form control.
   */
  protected $myName;

  protected $myValidators = array();

  protected $myAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  /** Object creator.
      @param $theName The name of this form control.
   */
  public function __construct( $theName )
  {
    if ($theName===null || $theName===false || $theName==='')
    {
      // We consider null, bool(false), and string(0) as empty. In these cases we set the name to false such that
      // we only have to test against false using the === operator in other parts of the code.
      $this->myName = false;
    }
    else
    {
      // We consider int(0), float(0), string(0) "", string(3) "0.0" as non empty names.
      $this->myName = (string)$theName;
    }

    /** @todo Consider throw exception when name is not a scalar or set name to false.
     */
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Adds validator @a $theValidator to this form control.
   */
  public function addValidator( $theValidator )
  {
    $this->myValidators[] = $theValidator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Sets the value of attribute with name @a $theName of this form control to @a $theValue. If @a $theValue is
      @c null, @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

   */
  public function setAttribute( $theName, $theValue )
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      unset( $this->myAttributes[$theName] );
    }
    else
    {
      if ($theName=='class' && isset($this->myAttributes[$theName]))
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
  /** Returns the value of an attribute.
      @param $theName The name of the requested attribute.
   */
  public function getAttribute( $theName )
  {
    return (isset($this->myAttributes[$theName])) ? $this->myAttributes[$theName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  abstract public function generate( $theParentName );

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the local name of this form control
   */
  public function getLocalName()
  {
    return $this->myName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the name this will be used for this form control when the form is submitted.
      @param The submit name of the parent form control of this form control.
   */
  protected function getSubmitName( $theParentSubmitName )
  {
    $obfuscator  = (isset($this->myAttributes['set_obfuscator'])) ? $this->myAttributes['set_obfuscator'] : null;
    $submit_name = ($obfuscator) ? $obfuscator->encode( $this->myName ) : $this->myName;

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
  public function getErrorMessages( $theRecursiveFlag=false )
  {
    return (isset($this->myAttributes['set_errmsg'])) ? $this->myAttributes['set_errmsg'] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the submitted value of this form control.
   */
  public function getSubmittedValue()
  {
    return $this->myAttributes['set_submitted_value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setErrorMessage( $theMessage )
  {
    $this->myAttributes['set_errmsg'][] = $theMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  abstract public function setValuesBase( &$theValues );

  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function validateBase( &$theInvalidFormControls );

  //--------------------------------------------------------------------------------------------------------------------
  abstract protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
