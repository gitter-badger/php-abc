<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 * @par Copyright:
 * Set Based IT Consultancy
 * $Date: 2013/03/04 19:02:37 $
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;
use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class Form
 * @package SetBased\Html
 */
class Form
{
  /*
   * The attributes of this form.
   * @var array
   */
  protected $myAttributes = array();

  /**
   * The field sets of this form.
   * @var \SetBased\Html\Form\FieldSet[]
   */
  protected $myFieldSets = array();

  /**
   * After a call to Form::loadSubmittedValues holds the names of the form controls of which the value has
   * changed.
   * @var array
   */
  protected $myChangedControls = array();

  /**
   * After a call to Form::loadSubmittedValues holds the white-listed submitted values.
   * @var array
   */
  protected $myValues = array();

  /**
   * After a call to Form::validate holds the names of the form controls which have valid one or more
   * validation tests.
   * @var array
   */
  protected $myInvalidControls = array();


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
   * @param $theArray array
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
  /** Creates a fieldset of type @a $theType and with name @a $theName and appends this fieldset to the list of field
   * sets of this form.
   *
   * @param  $theType string The class name of the fieldset which must be derived from class FieldSet. The following
   *                  alias are implemented:
   *                  - fieldset: class FieldSet
   * @param  $theName string The name (which might be empty) of the fieldset.
   *
   * @return  \SetBased\Html\Form\FieldSet
   */
  public function createFieldSet( $theType = 'fieldset', $theName = '' )
  {
    switch ($theType)
    {
      case 'fieldset':
        $type = '\SetBased\Html\Form\FieldSet';
        break;

      default:
        $type = $theType;
    }

    $tmp                 = new $type($theName);
    $this->myFieldSets[] = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Sets the value of attribute with name @a $theName of this form to @a $theValue. If @a $theValue is @c null,
   * @c false, or @c '' the attribute is unset.
   * @param $theName  The name of the attribute.
   * @param $theValue The value for the attribute.
   *                  Remarks:
   *                  Attribute 'method' is set default to 'post'.
   *                  Attribute 'action' is set default to the current URL.
  public function setAttribute( $theName, $theValue )
    if ($theValue==='' || $theValue===null || $theValue===false)
      unset($this->myAttributes[$theName]);
      if ($theName=='class' && isset($this->myAttributes[$theName]))
   * @todo Document how attribute class is handled.
   * @todo Document @a theExtendedFlag
  /**
   * Helper function for Form::setAttribute. Sets the value of attribute with name @a $theName of this form to
   * @a $theValue. If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
   *
   * @param $theName  string The name of the attribute.
   * @param $theValue mixed The value for the attribute.
   *
   * @todo Document how attribute class is handled.
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

    foreach ($this->myFieldSets as $fieldset)
    {
      $fieldSet->loadSubmittedValuesBase( $values, $this->myValues, $this->myChangedControls );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates all form controls of this form against all their installed validation checks.
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
  /** Returns @c true if and only if the value of one or more submitted form controls have changed. Otherwise returns
   * @c    false.
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   */
  public function haveChangedInputs()
  {
    return !empty($this->myChangedControls);
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
  protected function generateBody()
  {
    $ret = false;
    foreach ($this->myFieldSets as $fieldset)
    {
      $ret .= $fieldSet->generate( false );
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
  /**
   * Returns the submitted values of all form controls.
   * @return mixed A nested array of form control names (keys are form control names and (for complex form controls) values
   * are arrays or (for simple form controls) the submitted value).
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   * @return A nested array of form control names (keys are form control names and (for complex form controls) values
   * are arrays or (for simple form controls) the submitted value).
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   */
  public function getValues()
  {
    return $this->myValues;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValues( $theValues )
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->setValuesBase( $theValues );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form control names of which the value has been changed.
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
   * Returns all form controls which failed one or more validation tests.
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
   * Returns @c true if the element (of type submit or image) has been submitted.
   *
   * @param $theName string
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
        {
          return true;
        }
        break;

      case 'get':
        if (isset($_GET[$theName])) return true;
        {
          return true;
        }
        break;

      default:
        Html::error( "Unknown method '%s'.", $this->myAttributes['method'] );
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If no form control with @a $thePath exists an exception will
   * be thrown.
   *
   * @param  $thePath string  The path of the searched form control.
   *
   * @return Form\Control A form control with path $thePath.
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
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If no form control with @a $thePath exists @c null is
   * returned.
   *
   * @param  $thePath string The path of the searched form control.
   *
   * @return Form\Control | null A form control with path $thePath or @c null of no form control has been found.
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
    foreach ($this->myFieldSets as $control)
    {
      if ($control->getLocalName()===$parts[0])
      {
        if (sizeof( $parts )===1)
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
        if ($tmp)
        {
          return $tmp;
        }
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists an exception will
   * be thrown.
   *
   * @param  $theName string The name of the searched form control.
   *
   * @return Form\Control A form control with name $theName.
   * @sa findFormControlByName.
   */
  public function getFormControlByName( $theName )
  {
    $control = $this->findFormControlByName( $theName );

    if ($control===null) Html::error( sprintf( "No form control with name '%s' found.", $theName ) );
    {
      Html::error( "No form control with path '%s' exists.", $thePath );
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists @c null is
   * returned.
   *
   * @param  $theName string The name of the searched form control.
   *
   * @return Form\Control A form control with name $theName or @c null of no form control has been found.
   * @sa getFormControlByName.
   */
  public function findFormControlByName( $theName )
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      if ($fieldSet->getLocalName()===$theName) return $fieldSet;
      {
        return $fieldset;
      }

      $control = $fieldSet->findFormControlByName( $theName );
      if ($control)
      {
        return $control;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
    {
      Html::error( sprintf( "No form control with name '%s' found.", $theName ) );
    }
}

//----------------------------------------------------------------------------------------------------------------------
