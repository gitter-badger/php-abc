<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form;

use SetBased\Abc\Error\FallenException;
use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Misc\HtmlElement;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for generating [form](http://www.w3schools.com/tags/tag_form.asp) elements abn processing submitted data.
 */
class Form extends HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * After a call to {@link loadSubmittedValues} holds the names of the form controls of which the value has
   * changed.
   *
   * @var array
   */
  protected $myChangedControls = [];

  /**
   * The field sets of this form.
   *
   * @var FieldSet[]
   */
  protected $myFieldSets = [];

  /**
   * The (form) validators for validating the submitted values for this form.
   *
   * @var FormValidator[]
   */
  protected $myFormValidators = [];

  /**
   * After a call to {@link validate} holds the names of the form controls which have valid one or more
   * validation tests.
   *
   * @var array
   */
  protected $myInvalidControls = [];

  /**
   * After a call to {@link loadSubmittedValues} holds the white-listed submitted values.
   *
   * @var array
   */
  protected $myValues = [];


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
   * Returns true if array has one or more scalars. Otherwise, returns false.
   *
   * @param array $theArray The array.
   *
   * @return bool
   */
  public static function hasScalars($theArray)
  {
    $ret = false;
    foreach ($theArray as $tmp)
    {
      if (is_object($tmp))
      {
        $ret = true;
        break;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a fieldset to the fieldsets of this form.
   *
   * @param FieldSet $theFieldSet
   *
   * @return FieldSet
   */
  public function addFieldSet($theFieldSet)
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
  public function addFormValidator($theFormValidator)
  {
    $this->myFormValidators[] = $theFormValidator;

    return $theFormValidator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a fieldset  and appends this fieldset to the list of field sets of this form.
   *
   * @param string $theType The class name of the fieldset which must be derived from class FieldSet. The following
   *                        aliases are implemented:
   *                        * fieldset: class FieldSet
   * @param string $theName The name (which might be empty) of the fieldset.
   *
   * @return FieldSet
   */
  public function createFieldSet($theType = 'fieldset', $theName = '')
  {
    switch ($theType)
    {
      case 'fieldset':
        $fieldset = new FieldSet($theName);
        break;

      default:
        $fieldset = new $theType($theName);
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
   * Searches for a form control with by name. If more than one form control with the same name exists the first
   * found form control is returned. If no form control is found null is returned.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return Control
   */
  public function findFormControlByName($theName)
  {
    // Name must be string. Convert name to the string.
    $name = (string)$theName;

    foreach ($this->myFieldSets as $fieldSet)
    {
      if ($fieldSet->getLocalName()===$name) return $fieldSet;

      $control = $fieldSet->findFormControlByName($name);
      if ($control) return $control;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control by path. If more than one form control with same path exists the first found form
   * control is returned. If not form control is found null is returned.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return Control
   */
  public function findFormControlByPath($thePath)
  {
    if ($thePath===null || $thePath===false || $thePath==='' || $thePath==='/')
    {
      return null;
    }

    // $thePath must start with a leading slash.
    if (substr($thePath, 0, 1)!='/')
    {
      return null;
    }

    // Remove leading slash from the path.
    $path = substr($thePath, 1);

    foreach ($this->myFieldSets as $field_set)
    {
      $parts = preg_split('/\/+/', $path);

      if ($field_set->getLocalName()===$parts[0])
      {
        if (count($parts)===1)
        {
          return $field_set;
        }
        else
        {
          array_shift($parts);

          return $field_set->findFormControlByPath('/'.implode('/', $parts));
        }
      }
      else
      {
        $tmp = $field_set->findFormControlByPath($thePath);
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
    $ret = $this->generateStartTag();

    $ret .= $this->generateBody();

    $ret .= $this->generateEndTag();

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
   * Searches for a form control with by name. If more than one form control with the same name exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return Control
   */
  public function getFormControlByName($theName)
  {
    $control = $this->findFormControlByName($theName);
    if ($control===null)
    {
      throw new LogicException("Form control with path '%s' does not exists.", $theName);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control by path. If more than one form control with the same path exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return Control
   */
  public function getFormControlByPath($thePath)
  {
    $control = $this->findFormControlByPath($thePath);
    if ($control===null)
    {
      throw new LogicException("Form control with path '%s' does not exists.", $thePath);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form controls which failed one or more validation tests.
   *
   * @return array A nested array of form control names (keys are form control names and (for complex form controls)
   *               values are arrays or (for simple form controls) true).
   * @note This method should only be invoked after method Form::validate() has been invoked.
   */
  public function getInvalidControls()
  {
    return $this->myInvalidControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the current values of the form controls of this form. This method can be invoked be for
   * loadSubmittedValues has been invoked. The values returned are the values set with {@link setValues},
   * {@link mergeValues}, and {@link SimpleControl.setValue}. These values might not be white listed.
   * After {@link loadSubmittedValues} has been invoked use getValues.
   */
  public function getSetValues()
  {
    $ret = [];
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->getSetValuesBase($ret);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted values of all form controls.
   *
   * @note This method should only be invoked after method {@link loadSubmittedValues} has been invoked.
   *
   * @return array
   */
  public function getValues()
  {
    return $this->myValues;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if the value of one or more submitted form controls have changed. Otherwise returns false.
   *
   * @note This method should only be invoked after method {@link loadSubmittedValues} has been invoked.
   */
  public function haveChangedInputs()
  {
    return !empty($this->myChangedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the element (of type submit or image) has been submitted.
   *
   * @param string $theName
   *
   * @return bool
   */
  public function isSubmitted($theName)
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
        throw new FallenException('method', $this->myAttributes['method']);
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values. The white listed values can be obtained with method getValues.
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
        throw new FallenException('method', $this->myAttributes['method']);
    }

    // For all field sets load all submitted values.
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->loadSubmittedValuesBase($values, $this->myValues, $this->myChangedControls);
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
  public function mergeValues($theValues)
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->mergeValuesBase($theValues);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_form_autocomplete.asp). Possible values:
   * * Any value that evaluates to true will set the attribute to 'on'.
   * * Any value that evaluates to false will set the attribute to 'off'.
   * * Null will unset the attribute.
   *
   * @param mixed $theAutoCompleteFlag The auto complete.
   */
  public function setAttrAutoComplete($theAutoCompleteFlag)
  {
    $this->myAttributes['autocomplete'] = $theAutoCompleteFlag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [enctype](http://www.w3schools.com/tags/att_form_enctype.asp). Possible values:
   * * application/x-www-form-urlencoded (default)
   * * multipart/form-data
   * * text/plain
   *
   * @param string $theEncType The encoding type.
   */
  public function setAttrEncType($theEncType)
  {
    $this->myAttributes['enctype'] = $theEncType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [method](http://www.w3schools.com/tags/att_form_method.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string $theMethod The method.
   */
  public function setAttrMethod($theMethod)
  {
    $this->myAttributes['method'] = $theMethod;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this form. The values of form controls for which no explicit value is set
   * are set to null.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function setValues($theValues)
  {
    foreach ($this->myFieldSets as $fieldSet)
    {
      $fieldSet->setValuesBase($theValues);
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
      $fieldSet->validateBase($this->myInvalidControls);
    }

    $valid = empty($this->myInvalidControls);

    // If the submitted values are valid for all field sets validate the submitted values at form level.
    if ($valid)
    {
      foreach ($this->myFormValidators as $validator)
      {
        $valid = $validator->validate($this);
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
      $ret .= $fieldSet->generate('');
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generateEndTag()
  {
    return '</form>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generateStartTag()
  {
    return Html::generateTag('form', $this->myAttributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
