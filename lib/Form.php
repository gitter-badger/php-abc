<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

use SetBased\Html\Form\Control\FieldSet;
use SetBased\Html\Form\FormValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class Form
 *
 * @package SetBased\Html
 */
class Form
{

  /**
   * The attributes of this form.
   *
   * @var string[]
   */
  protected $myAttributes = array();

  /**
   * After a call to Form::loadSubmittedValues holds the names of the form controls of which the value has
   * changed.
   *
   * @var array
   */
  protected $myChangedControls = array();

  /**
   * The field sets of this form.
   *
   * @var FieldSet[]
   */
  protected $myFieldSets = array();

  /**
   * The (form) validators for validating the submitted values for this form.
   *
   * @var FormValidator[]
   */
  protected $myFormValidators = array();

  /**
   * After a call to Form::validate holds the names of the form controls which have valid one or more
   * validation tests.
   *
   * @var array
   */
  protected $myInvalidControls = array();

  /**
   * After a call to Form::loadSubmittedValues holds the white-listed submitted values.
   *
   * @var array
   */
  protected $myValues = array();


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->myAttributes['action'] = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
    $this->myAttributes['method'] = 'post';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns @c true if @a $theArray has one or more scalars. Otherwise, returns @c false.
   *
   * @param array $theArray
   *
   * @return bool
   */
  public static function hasScalars( $theArray )
  {
    $ret = false;
    foreach ($theArray as $tmp)
    {
      if (is_object( $tmp ))
      {
        $ret = true;
        break;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a field set to the field sets of this form.
   *
   * @param FieldSet $theFieldSet
   *
   * @return FieldSet
   */
  public function addFieldSet( $theFieldSet )
  {
    $this->myFieldSets[] = $theFieldSet;

    return $theFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form validator to list of form validators for this form.
   *
   * @param FormValidator $theFormValidator
   *
   * @return FormValidator
   */
  public function addFormValidator( $theFormValidator )
  {
    $this->myFormValidators[] = $theFormValidator;

    return $theFormValidator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a fieldset of @a $theType and with @a $theName and appends this fieldset to the list of field
   * sets of this form.
   *
   * @param string $theType The class name of the fieldset which must be derived from class FieldSet. The following
   *                        aliases are implemented:
   *                        - fieldset: class FieldSet
   * @param string $theName The name (which might be empty) of the fieldset.
   *
   * @return FieldSet
   */
  public function createFieldSet( $theType = 'fieldset', $theName = '' )
  {
    switch ($theType)
    {
      case 'fieldset':
        $fieldset = new FieldSet( $theName );
        break;

      default:
        $fieldset = new $theType( $theName );
    }

    $this->myFieldSets[] = $fieldset;

    return $fieldset;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Override this method in each child class for protection against CSRF attacks. This method is called at the end of
   * method loadSubmittedValues.
   * This method is a stub.
   */
  public function csrfCheck()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists @c null is
   * returned.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return \SetBased\Html\Form\Control\Control|\SetBased\Html\Form\Control\ComplexControl|null
   * @sa getFormControlByName.
   */
  public function findFormControlByName( $theName )
  {
    // Name must be string. Convert name to the string.
    $name = (string)$theName;

    foreach ($this->myFieldSets as $fieldSet)
    {
      if ($fieldSet->getLocalName()===$name) return $fieldSet;

      $control = $fieldSet->findFormControlByName( $name );
      if ($control) return $control;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If no form control with @a $thePath exists @c null is
   * returned.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return \SetBased\Html\Form\Control\Control|\SetBased\Html\Form\Control\ComplexControl|null
   * @sa GetFormControlByPath.
   */
  public function findFormControlByPath( $thePath )
  {
    if ($thePath===null || $thePath===false || $thePath==='' || $thePath==='/')
    {
      return null;
    }

    // $thePath must start with a leading slash.
    if (substr( $thePath, 0, 1 )!='/')
    {
      return null;
    }

    // Remove leading slash from the path.
    $path = substr( $thePath, 1 );

    foreach ($this->myFieldSets as $field_set)
    {
      $parts = preg_split( '/\/+/', $path );

      if ($field_set->getLocalName()===$parts[0])
      {
        if (count( $parts )===1)
        {
          return $field_set;
        }
        else
        {
          array_shift( $parts );

          return $field_set->findFormControlByPath( '/'.implode( '/', $parts ) );
        }
      }
      else
      {
        $tmp = $field_set->findFormControlByPath( $thePath );
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  public function generate()
  {
    $ret = $this->generateOpenTag();

    $ret .= $this->generateBody();

    $ret .= $this->generateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form control names of which the value has been changed.
   *
   * @return array A nested array of form control names (keys are form control names and (for complex form controls)
   *               values are arrays or (for simple form controls) @c true).
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   */
  public function getChangedControls()
  {
    return $this->myChangedControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists an exception will
   * be thrown.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return \SetBased\Html\Form\Control\Control|\SetBased\Html\Form\Control\ComplexControl
   * @sa findFormControlByName.
   */
  public function getFormControlByName( $theName )
  {
    $control = $this->findFormControlByName( $theName );

    if ($control===null) Html::error( "No form control with path '%s' exists.", $theName );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If no form control with @a $thePath exists an exception will
   * be thrown.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return \SetBased\Html\Form\Control\Control|\SetBased\Html\Form\Control\ComplexControl
   * @sa FindFormControlByPath.
   */
  public function getFormControlByPath( $thePath )
  {
    $control = $this->findFormControlByPath( $thePath );
    if ($control===null) Html::error( "No form control with path '%s' exists.", $thePath );

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form controls which failed one or more validation tests.
   *
   * @return array A nested array of form control names (keys are form control names and (for complex form controls)
   *               values are arrays or (for simple form controls) @c true).
   * @note This method should only be invoked after method Form::validate() has been invoked.
   */
  public function getInvalidControls()
  {
    return $this->myInvalidControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the current values of the form controls of this form. This method can be invoked be for
   * loadSubmittedValues has been invoked. The values returned are the values set with Form::setValues,
   * Form::mergeValues, and SimpleControl::setValue. These values might not be white listed.
   * After loadSubmittedValues has been invoked use getValues.
   */
  public function getSetValues()
  {
    $ret = array();
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->getSetValuesBase( $ret );
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted values of all form controls.
   *
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   * @return array
   */
  public function getValues()
  {
    return $this->myValues;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns @c true if and only if the value of one or more submitted form controls have changed. Otherwise returns
   *
   * @c    false.
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   */
  public function haveChangedInputs()
  {
    return !empty($this->myChangedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns @c true if the element (of type submit or image) has been submitted.
   *
   * @param string $theName
   *
   * @return bool
   */
  public function isSubmitted( $theName )
  {
    /** @todo check value is white list. */
    switch ($this->myAttributes['method'])
    {
      case 'post':
        if (isset($_POST[$theName])) return true;
        break;

      case 'get':
        if (isset($_GET[$theName])) return true;
        break;

      default:
        Html::error( "Unknown method '%s'.", $this->myAttributes['method'] );
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values.
   * The white listed values can be obtained with method getValues.
   */
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
        Html::error( "Unknown method '%s'.", $this->myAttributes['method'] );
        $values = ''; // Keep our IDE happy.
    }

    // For all field sets load all submitted values.
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->loadSubmittedValuesBase( $values, $this->myValues, $this->myChangedControls );
    }

    // Defend against CSRF attacks.
    $this->csrfCheck();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this form. The values of form controls for which no explicit value is set
   * are left on changed
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function mergeValues( $theValues )
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->mergeValuesBase( $theValues );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute @a $theName of this form to @a $theValue.
   * * If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
   * * If @a $theName is 'class' the @a $theValue is appended to space separated list of classes (unless the above rule
   *   applies.)
   *
   * @param string      $theName  The name of the attribute.
   * @param string|null $theValue The value for the attribute.
   */
  public function setAttribute( $theName, $theValue )
  {
    if ($theValue===null || $theValue===false || $theValue==='')
    {
      unset($this->myAttributes[$theName]);
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
  /**
   * Sets the values of the form controls of this form. The values of form controls for which no explicit value is set
   * are set to null.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function setValues( $theValues )
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->setValuesBase( $theValues );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates all form controls of this form against all their installed validation checks. After all form controls
   * passed their validations validates the form itself against all its installed validation checks.
   *
   * @return bool True if the submitted values are valid, false otherwise.
   */
  public function validate()
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->validateBase( $this->myInvalidControls );
    }

    $valid = empty($this->myInvalidControls);

    // If the submitted values are valid for all field sets validate the submitted values at form level.
    if ($valid)
    {
      foreach ($this->myFormValidators as $validator)
      {
        $valid = $validator->validate( $this );
        if (!$valid)
        {
          // Stop immediately after the first valid validator (validators (may) depend on successful validations of
          // their predecessors).
          break;
        }
      }
    }

    return ($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generateBody()
  {
    $ret = '';
    foreach ($this->myFieldSets as $fieldSet)
    {
      $ret .= $fieldSet->generate( '' );
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generateCloseTag()
  {
    $ret = '</form>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generateOpenTag()
  {
    $ret = '<form';
    foreach ($this->myAttributes as $name => $value)
    {
      // Ignore attributes starting with an underscore.
      if ($name[0]!='_') $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
