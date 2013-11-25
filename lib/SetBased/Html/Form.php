<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

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
   * @var \SetBased\Html\Form\Control\FieldSet[]
   */
  protected $myFieldSets = array();

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
  static public function hasScalars( $theArray )
  {
    $ret = false;
    foreach ($theArray as $tmp)
    {
      if (is_scalar( $tmp ))
      {
        $ret = true;
        break;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a fieldset of @a $theType and with @a $theName and appends this fieldset to the list of field
   * sets of this form.
   *
   * @param string $theType The class name of the fieldset which must be derived from class FieldSet. The following
   *                        alias are implemented:
   *                        - fieldset: class FieldSet
   * @param string $theName The name (which might be empty) of the fieldset.
   *
   * @return  \SetBased\Html\Form\Control\FieldSet
   */
  public function createFieldSet( $theType = 'fieldset', $theName = '' )
  {
    switch ($theType)
    {
      case 'fieldset':
        $type = '\SetBased\Html\Form\Control\FieldSet';
        break;

      default:
        $type = $theType;
    }

    $tmp                 = new $type($theName);
    $this->myFieldSets[] = $tmp;

    return $tmp;
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
    foreach ($this->myFieldSets as $fieldSet)
    {
      if ($fieldSet->getLocalName()===$theName) return $fieldSet;

      $control = $fieldSet->findFormControlByName( $theName );
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
    if (substr( $thePath, 0, 1 )!=='/')
    {
      return null;
    }

    $parts = preg_split( '/\/+/', $thePath );
    foreach ($this->myFieldSets as $field_set)
    {
      if ($field_set->getLocalName()===$parts[0])
      {
        if (sizeof( $parts )===1)
        {
          return $field_set;
        }
        else
        {
          array_shift( $parts );

          return $field_set->findFormControlByPath( implode( '/', $parts ) );
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
   * @return array A nested array of form control names (keys are form control names and (for complex form controls) values
   * are arrays or (for simple form controls) @c true).
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
   * @return array A nested array of form control names (keys are form control names and (for complex form controls) values
   * are arrays or (for simple form controls) @c true).
   * @note This method should only be invoked after method Form::validate() has been invoked.
   */
  public function getInvalidControls()
  {
    return $this->myInvalidControls;
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
   *
   */
  public function loadSubmittedValues()
  {
    $values = '';
    switch ($this->myAttributes['method'])
    {
      case 'post':
        $values = & $_POST;
        break;

      case 'get':
        $values = & $_GET;
        break;

      default:
        Html::error( "Unknown method '%s'.", $this->myAttributes['method'] );
    }

    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->loadSubmittedValuesBase( $values, $this->myValues, $this->myChangedControls );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param mixed $theValues
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
   * Validates all form controls of this form against all their installed validation checks.
   *
   * @return bool @c true if and only if all form controls fulfill all their validation checks. Otherwise, returns @c
   * false.@note
   * This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   */
  public function validate()
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->validateBase( $this->myInvalidControls );
    }

    return (empty($this->myInvalidControls));
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
    $ret = "</form>\n";

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
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    return $ret;
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
  protected function setAttributeBase( $theName, $theValue )
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
}

//----------------------------------------------------------------------------------------------------------------------
