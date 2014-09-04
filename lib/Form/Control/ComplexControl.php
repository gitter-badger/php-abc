<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

/**
 * Class ComplexControl
 *
 * @package SetBased\Html\Form\Control
 */
class ComplexControl extends Control
{
  /**
   * The child HTML form controls of this form control.
   *
   * @var ComplexControl[]|Control[]
   */
  protected $myControls = array();

  /**
   * The child form controls of this form control with invalid sumitted values.
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
   * @param string $theControl
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckBoxesControl|RadiosControl
   */
  public function addFormControl( $theControl )
  {
    $this->myControls[] = $theControl;

    return $theControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A factory for creating form control objects.
   *
   * @param string $theType The class name of the form control which must be derived from class FormControl.
   * @param string $theName The name (which might be empty for complex form controls) of the form control.
   *
   * @return ComplexControl|SimpleControl|SelectControl|CheckBoxesControl|RadiosControl
   */
  public function createFormControl( $theType, $theName )
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
   * Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists @c null is
   * returned.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return ComplexControl|Control
   * @sa getFormControlByName.
   */
  public function findFormControlByName( $theName )
  {
    // Name must be string. Convert name to the string.
    $name = (string)$theName;

    foreach ($this->myControls as $control)
    {
      if ($control->myName===$name) return $control;

      if (is_a( $control, '\SetBased\Html\Form\Control\ComplexControl' ))
      {
        $tmp = $control->findFormControlByName( $name );
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If not form control with @a $thePath exists @c null is
   * returned.
   *
   * @param string $thePath The path of the searched form control.
   *
   * @return ComplexControl|Control
   * @sa getFormControlByPath.
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

    foreach ($this->myControls as $control)
    {
      $parts = preg_split( '/\/+/', $path );

      if ($control->myName==$parts[0])
      {
        if (sizeof( $parts )==1)
        {
          return $control;
        }
        else
        {
          if (is_a( $control, '\SetBased\Html\Form\Control\ComplexControl' ))
          {
            array_shift( $parts );
            $tmp = $control->findFormControlByPath( '/'.implode( '/', $parts ) );
            if ($tmp) return $tmp;
          }
        }
      }
      elseif ($control->myName==='' && is_a( $control, '\SetBased\Html\Form\Control\ComplexControl' ))
      {
        $tmp = $control->findFormControlByPath( $thePath );
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $submit_name = $this->getSubmitName( $theParentName );

    $ret = '';
    foreach ($this->myControls as $control)
    {
      $ret .= $control->generate( $submit_name );
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param bool $theRecursiveFlag
   *
   * @return array|null
   */
  public function getErrorMessages( $theRecursiveFlag = false )
  {
    $ret = array();
    if ($theRecursiveFlag)
    {
      foreach ($this->myControls as $control)
      {
        $tmp = $control->getErrorMessages( true );
        if (is_array( $tmp ))
        {
          $ret = array_merge( $ret, $tmp );
        }
      }
    }

    if (isset($this->myErrorMessages))
    {
      $ret = array_merge( $ret, $this->myErrorMessages );
    }

    if (empty($ret)) $ret = null;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists an exception will
   * be thrown.
   *
   * @param string $theName The name of the searched form control.
   *
   * @return  ComplexControl|Control
   * @sa findFormControlByName.
   */
  public function getFormControlByName( $theName )
  {
    $control = $this->findFormControlByName( $theName );

    if ($control===null) Html::error( "No form control with name '%s' found.", $theName );

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
   * @return ComplexControl|Control
   * @sa findFormControlByPath.
   */
  public function getFormControlByPath( $thePath )
  {
    $control = $this->findFormControlByPath( $thePath );

    if ($control===null) Html::error( "No form control with path '%s' exists.", $thePath );

    return $control;
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
   * Returns true if the submitted values of this form control and this form control are valid. Otherwise, returns
   * false.
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
  public function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if ($this->myName==='')
    {
      $tmp1 = & $theSubmittedValue;
      $tmp2 = & $theWhiteListValue;
      $tmp3 = & $theChangedInputs;
    }
    else
    {
      $tmp1 = & $theSubmittedValue[$submit_name];
      $tmp2 = & $theWhiteListValue[$this->myName];
      $tmp3 = & $theChangedInputs[$this->myName];
    }

    foreach ($this->myControls as $control)
    {
      $control->loadSubmittedValuesBase( $tmp1, $tmp2, $tmp3 );
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
   * @param mixed $theValues
   */
  public function setValuesBase( &$theValues )
  {
    if ($this->myName!=='') $values = & $theValues[$this->myName];
    else                    $values = & $theValues;

    foreach ($this->myControls as $control)
    {
      $control->setValuesBase( $values );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  public function validateBase( &$theInvalidFormControls )
  {
    $tmp = array();

    // First, validate all child form controls.
    foreach ($this->myControls as $control)
    {
      $child_valid = $control->validateBase( $tmp );
      if (!$child_valid) $this->myInvalidControls[] = $control;
    }

    if (empty($tmp))
    {
      // All the individual child form controls are valid. Validate the child form controls as a whole.
      $valid = $this->validateSelf( $theInvalidFormControls );
    }
    else
    {
      // One or more input values are invalid. Append the invalid form controls to $theInvalidFormControls.
      $theInvalidFormControls[] = $tmp;

      $valid = false;
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param  $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateSelf( &$theInvalidFormControls )
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate( $this );
      if ($valid!==true)
      {
        $theInvalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
