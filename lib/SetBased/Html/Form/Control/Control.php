<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

//---------------------------------------------------------------------------------------------------------------------
/**
 * Class Control
 *
 * @package SetBased\Html\Form\Control
 */
abstract class Control
{
  /**
   * The HTML attributes of this form control.
   *
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * The (local) name of this form control.
   *
   * @var string
   */
  protected $myName;

  /**
   * The obfuscator to obfuscate the (submitted) name of this form control.
   *
   * @var \SetBased\Html\Obfuscator
   */
  protected $myObfuscator;

  /**
   * The HTML code that will be appended after the HTML code of this form control.
   *
   * @var string
   */
  protected $myPostfix;

  /**
   * The HTML code that will be inserted before the HTML code of this form control.
   *
   * @var string
   */
  protected $myPrefix;

  /**
   * The validators that will be used to validate this form control.
   *
   * @var \SetBased\Html\Form\ControlValidator[]
   */
  protected $myValidators = array();

  //--------------------------------------------------------------------------------------------------------------------
  /** Object creator.
   *
   * @param string $theName The (local) name of this form control.
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
   * @param \SetBased\Html\Form\ControlValidator $theValidator
   */
  public function addValidator( $theValidator )
  {
    $this->myValidators[] = $theValidator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return mixed
   */
  abstract public function generate( $theParentName );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an attribute.
   *
   * @param string $theName The name of the requested attribute.
   *
   * @return string|null
   */
  public function getAttribute( $theName )
  {
    return (isset($this->myAttributes[$theName])) ? $this->myAttributes[$theName] : null;
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
   * Returns the submitted value of this form control.
   */
  public function getSubmittedValue()
  {
    return $this->myAttributes['set_submitted_value'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute with name @a $theName of this form control to @a $theValue. If @a $theValue is
   *
   * @c null, @c false, or @c '' the attribute is unset.
   *
   * @param string $theName  The name of the attribute.
   * @param mixed  $theValue The value for the attribute.
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
   * @param string $theMessage
   */
  public function setErrorMessage( $theMessage )
  {
    $this->myAttributes['set_errmsg'][] = $theMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the obfuscator of the form control.
   *
   * @param \SetBased\Html\Obfuscator $theObfuscator The obfuscator for the form control.
   */
  public function setObfuscator( $theObfuscator )
  {
    $this->myObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is inserted before the HTML code of this form control to @a $theHtmlSnippet.
   *
   * @param string $theHtmlSnippet
   */
  public function setPostfix( $theHtmlSnippet )
  {
    $this->myPostfix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is appended after the HTML code of this form control to @a $theHtmlSnippet.
   *
   * @param string $theHtmlSnippet
   */
  public function setPrefix( $theHtmlSnippet )
  {
    $this->myPrefix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param mixed $theValues
   */
  abstract public function setValuesBase( &$theValues );

  //--------------------------------------------------------------------------------------------------------------------
  /** Returns the name this will be used for this form control when the form is submitted.
   *
   * @param string $theParentSubmitName The submit name of the parent form control of this form control.
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
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  abstract protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs );

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  abstract protected function validateBase( &$theInvalidFormControls );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
