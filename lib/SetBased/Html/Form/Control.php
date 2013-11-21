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

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class Control Abstract class for objects for generation HTML code for form control.
 * @package SetBased\Html\Form
 */
abstract class Control
{
  /**
   * The (local) name of this form control.
   * @var string
   */
  protected $myName;

  /**
   * The HTML attributes of this form control.
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * The validators that will be used to validate this form control.
   * @var \SetBased\Html\Form\ControlValidator[]
   */
  protected $myValidators = array();

  /**
   * The obfuscator to obfuscate the (submitted) name of this form control.
   * @var \SetBased\Html\Obfuscator
   */
  protected $myObfuscator;

  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   * @var \SetBased\Html\Obfuscator
   */
  protected $myCleaner;

 //--------------------------------------------------------------------------------------------------------------------
  /** Object creator.
   *
   * @param $theName string The (local) name of this form control.
   */
  public function __construct( $theName )
  {
    if ($theName===null || $theName===false || $theName==='')
    {
      // We consider null, bool(false), and string(0) as empty. In these cases we set the name to '' such that
      // we only have to test against '' using the === operator in other parts of the code.
      $this->myName = '';
    }
    else
    {
      // We consider int(0), float(0), string(0) "", string(3) "0.0" as non empty names.
      $this->myName = (string)$theName;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a validator for this form control.
   *
   * @param $theValidator ControlValidator
   */
  public function addValidator( $theValidator )
  {
    $this->myValidators[] = $theValidator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Sets the value of attribute with name @a $theName of this form control to @a $theValue. If @a $theValue is
   * @c null, @c false, or @c '' the attribute is unset.
   *
   * @param $theName  string The name of the attribute.
   * @param $theValue mixed The value for the attribute.
   */
  public function setAttribute( $theName, $theValue )
  {
    if ($theValue===null || $theValue===false || $theValue==='')
    {
      unset($this->myAttributes[$theName]);
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
  /**
   * Returns the value of an attribute.
   *
   * @param $theName string The name of the requested attribute.
   *
   * @return string|null
   */
  public function getAttribute( $theName )
  {
    return (isset($this->myAttributes[$theName])) ? $this->myAttributes[$theName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theParentName
   *
   * @return mixed
   */
  abstract public function generate( $theParentName );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the local name of this form control
   *
   * @return string
   */
  public function getLocalName()
  {
    return $this->myName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the the error messages of this form control.
   *
   * @param bool $theRecursiveFlag
   *
   * @return null|string
   */
  public function getErrorMessages( $theRecursiveFlag = false )
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
  /**
   * @param $theMessage
   */
  public function setErrorMessage( $theMessage )
  {
    $this->myAttributes['set_errmsg'][] = $theMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the obfuscator of the form control.
   *
   * @param $theObfuscator \SetBased\Html\Obfuscator The obfuscator for the form control.
   */
  public function setObfuscator( $theObfuscator )
  {
    $this->myObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theValues
   *
   * @return mixed
   */
  abstract public function setValuesBase( &$theValues );

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the name this will be used for this form control when the form is submitted.
   *
   * @param $theParentSubmitName string The submit name of the parent form control of this form control.
   *
   * @return string
   */
  protected function getSubmitName( $theParentSubmitName )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if ($theParentSubmitName!=='')
    {
      if ($submit_name!=='') $global_name = $theParentSubmitName.'['.$submit_name.']';
      else                   $global_name = $theParentSubmitName;
    }
    else
    {
      $global_name = $submit_name;
    }

    return $global_name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theInvalidFormControls
   *
   * @return mixed
   */
  abstract protected function validateBase( &$theInvalidFormControls );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param $theSubmittedValue array
   * @param $theWhiteListValue array
   * @param $theChangedInputs  array
   *
   * @return mixed
   */
  abstract protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
