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
   * The value of this form control, i.e. a nested array of the values of the child form controls.
   *
   * @var mixed
   */
  protected $myValue;

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
    switch ($theType)
    {
      case 'text':
        $type = '\SetBased\Html\Form\Control\TextControl';
        break;

      case 'password':
        $type = '\SetBased\Html\Form\Control\PasswordControl';
        break;

      case 'checkbox':
        $type = '\SetBased\Html\Form\Control\CheckboxControl';
        break;

      case 'radio':
        $type = '\SetBased\Html\Form\Control\RadioControl';
        break;

      case 'submit':
        $type = '\SetBased\Html\Form\Control\SubmitControl';
        break;

      case 'image':
        $type = '\SetBased\Html\Form\Control\ImageControl';
        break;

      case 'reset':
        $type = '\SetBased\Html\Form\Control\ResetControl';
        break;

      case 'button':
        $type = '\SetBased\Html\Form\Control\ButtonControl';
        break;

      case 'hidden':
        $type = '\SetBased\Html\Form\Control\HiddenControl';
        break;

      case 'file':
        $type = '\SetBased\Html\Form\Control\FileControl';
        break;

      case 'invisible':
        $type = '\SetBased\Html\Form\Control\InvisibleControl';
        break;

      case 'textarea':
        $type = '\SetBased\Html\Form\Control\TextAreaControl';
        break;

      case 'complex':
        $type = '\SetBased\Html\Form\Control\ComplexControl';
        break;

      case 'select':
        $type = '\SetBased\Html\Form\Control\SelectControl';
        break;

      case 'span':
        $type = '\SetBased\Html\Form\Control\SpanControl';
        break;

      case 'div':
        $type = '\SetBased\Html\Form\Control\DivControl';
        break;

      case 'a':
        $type = '\SetBased\Html\Form\Control\LinkControl';
        break;

      case 'constant':
        $type = '\SetBased\Html\Form\Control\ConstantControl';
        break;

      case 'radios':
        $type = '\SetBased\Html\Form\Control\RadiosControl';
        break;

      case 'checkboxes':
        $type = '\SetBased\Html\Form\Control\CheckboxesControl';
        break;

      default:
        $type = $theType;
    }

    $tmp                = new $type($theName);
    $this->myControls[] = $tmp;

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
   * @return ComplexControl|Control
   * @sa getFormControlByName.
   */
  public function findFormControlByName( $theName )
  {
    foreach ($this->myControls as $control)
    {
      if ($control->myName===$theName) return $control;

      if (is_a( $control, '\SetBased\Html\Form\Control\ComplexControl' ))
      {
        $tmp = $control->findFormControlByName( $theName );
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
      $ret .= "\n";
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
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
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
   * Returns the submitted value of this form control.
   *
   * returns string
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
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
      $control->validateBase( $tmp );
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
