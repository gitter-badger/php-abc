<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Error\LogicException;

/**
 * A complex from control consists of one ore more complex or simple form controls.
 */
class ComplexControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The child form controls of this form control.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $myControls = [];

  /**
   * The child form controls of this form control with invalid submitted values.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $myInvalidControls;

  /**
   * The value of this form control, i.e. a nested array of the values of the child form controls.
   *
   * @var mixed
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to this complex form control.
   *
   * @param Control $theControl The from control added.
   *
   * @return Control The added form control.
   */
  public function addFormControl($theControl)
  {
    $this->myControls[] = $theControl;

    return $theControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A factory for creating form control objects. The created form control is added to this complex control.
   *
   * @param string $theType The class name of the form control which must be derived from class FormControl.
   * @param string $theName The name (which might be empty for complex form controls) of the form control.
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckBoxesControl|RadiosControl
   */
  public function createFormControl($theType, $theName)
  {
    $control = null;
    switch ($theType)
    {
      case 'text':
        $control = new TextControl($theName);
        break;

      case 'password':
        $control = new PasswordControl($theName);
        break;

      case 'checkbox':
        $control = new CheckboxControl($theName);
        break;

      case 'radio':
        $control = new RadioControl($theName);
        break;

      case 'submit':
        $control = new SubmitControl($theName);
        break;

      case 'image':
        $control = new ImageControl($theName);
        break;

      case 'reset':
        $control = new ResetControl($theName);
        break;

      case 'button':
        $control = new ButtonControl($theName);
        break;

      case 'hidden':
        $control = new HiddenControl($theName);
        break;

      case 'file':
        $control = new FileControl($theName);
        break;

      case 'invisible':
        $control = new InvisibleControl($theName);
        break;

      case 'textarea':
        $control = new TextAreaControl($theName);
        break;

      case 'complex':
        $control = new ComplexControl($theName);
        break;

      case 'select':
        $control = new SelectControl($theName);
        break;

      case 'span':
        $control = new SpanControl($theName);
        break;

      case 'div':
        $control = new DivControl($theName);
        break;

      case 'a':
        $control = new LinkControl($theName);
        break;

      case 'constant':
        $control = new ConstantControl($theName);
        break;

      case 'radios':
        $control = new RadiosControl($theName);
        break;

      case 'checkboxes':
        $control = new CheckboxesControl($theName);
        break;

      case 'html':
        $control = new HtmlControl($theName);
        break;

      default:
        $control = new $theType($theName);
    }

    $this->myControls[] = $control;

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with by name. If more than one form control with the same name exists the first
   * found form control is returned. If no form control is found null is returned.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return ComplexControl|Control
   */
  public function findFormControlByName($theName)
  {
    // Name must be string. Convert name to the string.
    $name = (string)$theName;

    foreach ($this->myControls as $control)
    {
      if ($control->myName===$name) return $control;

      if (is_a($control, '\SetBased\Abc\Form\Control\ComplexControl'))
      {
        $tmp = $control->findFormControlByName($name);
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control by path. If more than one form control with same path exists the first found form
   * control is returned. If not form control is found null is returned.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return ComplexControl|Control
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

    foreach ($this->myControls as $control)
    {
      $parts = preg_split('/\/+/', $path);

      if ($control->myName==$parts[0])
      {
        if (count($parts)==1)
        {
          return $control;
        }
        else
        {
          if (is_a($control, '\SetBased\Abc\Form\Control\ComplexControl'))
          {
            array_shift($parts);
            $tmp = $control->findFormControlByPath('/'.implode('/', $parts));
            if ($tmp) return $tmp;
          }
        }
      }
      elseif ($control->myName==='' && is_a($control, '\SetBased\Abc\Form\Control\ComplexControl'))
      {
        $tmp = $control->findFormControlByPath($thePath);
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate($theParentName)
  {
    $submit_name = $this->getSubmitName($theParentName);

    $ret = '';
    foreach ($this->myControls as $control)
    {
      $ret .= $control->generate($submit_name);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array of all error messages of the child form controls of this complex form controls.
   *
   * @param bool $theRecursiveFlag If set error messages of complex child controls of this complex form controls are
   *                               fetched also.
   *
   * @return array|null
   */
  public function getErrorMessages($theRecursiveFlag = false)
  {
    $ret = [];
    if ($theRecursiveFlag)
    {
      foreach ($this->myControls as $control)
      {
        $tmp = $control->getErrorMessages(true);
        if (is_array($tmp))
        {
          $ret = array_merge($ret, $tmp);
        }
      }
    }

    if (isset($this->myErrorMessages))
    {
      $ret = array_merge($ret, $this->myErrorMessages);
    }

    if (empty($ret)) $ret = null;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control with by name. If more than one form control with the same name exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return ComplexControl|Control
   */
  public function getFormControlByName($theName)
  {
    $control = $this->findFormControlByName($theName);
    if ($control===null)
    {
      throw new LogicException("Form control with name '%s' does not exists.", $theName);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control by path. If more than one form control with the same path exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return ComplexControl|Control
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
   * {@inheritdoc}
   */
  public function getSetValuesBase(&$theValues)
  {
    if ($this->myName==='')
    {
      $tmp = &$theValues;
    }
    else
    {
      $theValues[$this->myName] = [];
      $tmp                      = &$theValues[$this->myName];
    }

    foreach ($this->myControls as $control)
    {
      $control->getSetValuesBase($tmp);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @returns array
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the submitted values of this complex form control and this child form control are valid.
   * Otherwise, returns false.
   *
   * @return bool
   */
  public function isValid()
  {
    return (!$this->myInvalidControls && !$this->myErrorMessages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if ($this->myName==='')
    {
      $tmp1 = &$theSubmittedValue;
      $tmp2 = &$theWhiteListValue;
      $tmp3 = &$theChangedInputs;
    }
    else
    {
      $tmp1 = &$theSubmittedValue[$submit_name];
      $tmp2 = &$theWhiteListValue[$this->myName];
      $tmp3 = &$theChangedInputs[$this->myName];
    }

    foreach ($this->myControls as $control)
    {
      $control->loadSubmittedValuesBase($tmp1, $tmp2, $tmp3);
    }

    if ($this->myName!=='')
    {
      if (empty($theWhiteListValue[$this->myName])) unset($theWhiteListValue[$this->myName]);
      if (empty($theChangedInputs[$this->myName])) unset($theChangedInputs[$this->myName]);
    }

    // Set the submitted values.
    $this->myValue = $tmp2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are not affected.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function mergeValuesBase($theValues)
  {
    if ($this->myName==='')
    {
      $values = &$theValues;
    }
    elseif (isset($theValues[$this->myName]))
    {
      $values = &$theValues[$this->myName];
    }
    else
    {
      $values = null;
    }

    if ($values!==null)
    {
      foreach ($this->myControls as $control)
      {
        $control->mergeValuesBase($values);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are set to null.
   *
   * @param mixed $theValues The values as a nested array.
   */
  public function setValuesBase($theValues)
  {
    if ($this->myName==='')
    {
      $values = &$theValues;
    }
    elseif (isset($theValues[$this->myName]))
    {
      $values = &$theValues[$this->myName];
    }
    else
    {
      $values = null;
    }

    foreach ($this->myControls as $control)
    {
      $control->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes a validators on the child form controls of this form complex control. If  and only if all child form
   * controls are valid the validators of this complex control are executed.
   *
   * @param array $theInvalidFormControls A nested array of invalid form controls.
   *
   * @return bool True if and only if all form controls are valid.
   */
  public function validateBase(&$theInvalidFormControls)
  {
    $valid = true;

    // First, validate all child form controls.
    foreach ($this->myControls as $control)
    {
      $valid = $control->validateBase($theInvalidFormControls) && $valid;
    }

    if ($valid)
    {
      // All the child form controls are valid. Validate this complex form control.
      foreach ($this->myValidators as $validator)
      {
        $valid = $validator->validate($this);
        if ($valid!==true)
        {
          $theInvalidFormControls[] = $this;
          break;
        }
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
