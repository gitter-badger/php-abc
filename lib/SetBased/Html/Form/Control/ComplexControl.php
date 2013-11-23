<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

class ComplexControl extends Control
{
  /**
   * The child HTML form controls of this form control.
   * @var ComplexControl[]|Control[]
   */
  protected $myControls = array();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A factory for creating form control objects.
   *
   * @param  $theType string The class name of the form control which must be derived from class FormControl.
   * @param  $theName string The name (which might be empty for complex form controls) of the form control.
   *
   * @return ComplexControl|SimpleControl
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

    if (isset($this->myAttributes['set_errmsg']))
    {
      $ret = array_merge( $ret, $this->myAttributes['set_errmsg'] );
    }

    if (empty($ret)) $ret = false;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
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

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $tmp2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    if ($this->myName!=='') $values = & $theValues[$this->myName];
    else                    $values = & $theValues;

    foreach ($this->myControls as $control)
    {
      $control->SetValuesBase( $values );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
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
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If no form control with @a $thePath exists an exception will
   * be thrown.
   *
   * @param  $thePath string The path of the searched form control.
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
   * Searches for the form control with path @a $thePath. If more than one form control with path @a $thePath
   * exists the first found form control is returned. If not form control with @a $thePath exists @c null is
   * returned.
   *
   * @param  $thePath string The path of the searched form control.
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

    $parts = preg_split( '/\/+/', $thePath );

    foreach ($this->myControls as $control)
    {
      if ($control->getLocalName()===$parts[0] && sizeof( $parts )===1)
      {
        return $control;
      }
      else
      {
        if (sizeof( $parts )===1) array_shift( $parts );
        if (is_a( $control, '\SetBased\Html\Form\Control\ComplexControl' ))
        {
          $control->findFormControlByPath( implode( '/', $parts ) );
        }
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists an exception will
   * be thrown.
   *
   * @param  $theName string The name of the searched form control.
   *
   * @return  ComplexControl|Control
   * @sa findFormControlByName.
   */
  public function getFormControlByName( $theName )
  {
    $control = $this->findFormControlByName( $theName );

    if ($control===null) Html::error( "No form control with name '%s' found.", $theName );
    {
      Html::error( "No form control with name '%s' found.", $theName );
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for the form control with name @a $theName. If more than one form control with name @a $theName
   * exists the first found form control is returned. If no form control with @a $theName exists @c null is
   * returned.
   *
   * @param  $theName string The name of the searched form control.
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
