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
    $control = null;
    switch ($theType)
    {
      case 'text':
        $control = new \SetBased\Html\Form\Control\TextControl( $theName );
        break;

      case 'password':
        $control = new \SetBased\Html\Form\Control\PasswordControl( $theName );
        break;

      case 'checkbox':
        $control = new \SetBased\Html\Form\Control\CheckboxControl( $theName );
        break;

      case 'radio':
        $control = new \SetBased\Html\Form\Control\RadioControl( $theName );
        break;

      case 'submit':
        $control = new \SetBased\Html\Form\Control\SubmitControl( $theName );
        break;

      case 'image':
        $control = new \SetBased\Html\Form\Control\ImageControl( $theName );
        break;

      case 'reset':
        $control = new \SetBased\Html\Form\Control\ResetControl( $theName );
        break;

      case 'button':
        $control = new \SetBased\Html\Form\Control\ButtonControl( $theName );
        break;

      case 'hidden':
        $control = new \SetBased\Html\Form\Control\HiddenControl( $theName );
        break;

      case 'file':
        $control = new \SetBased\Html\Form\Control\FileControl( $theName );
        break;

      case 'invisible':
        $control = new \SetBased\Html\Form\Control\InvisibleControl( $theName );
        break;

      case 'textarea':
        $control = new \SetBased\Html\Form\Control\TextAreaControl( $theName );
        break;

      case 'complex':
        $control = new \SetBased\Html\Form\Control\ComplexControl( $theName );
        break;

      case 'select':
        $control = new \SetBased\Html\Form\Control\SelectControl( $theName );
        break;

      case 'span':
        $control = new \SetBased\Html\Form\Control\SpanControl( $theName );
        break;

      case 'div':
        $control = new \SetBased\Html\Form\Control\DivControl( $theName );
        break;

      case 'a':
        $control = new \SetBased\Html\Form\Control\LinkControl( $theName );
        break;

      case 'constant':
        $control = new \SetBased\Html\Form\Control\ConstantControl( $theName );
        break;

      case 'radios':
        $control = new \SetBased\Html\Form\Control\RadiosControl( $theName );
        break;

      case 'checkboxes':
        $control = new \SetBased\Html\Form\Control\CheckboxesControl( $theName );
        break;

      default:
        if (class_exists($theType))
        {
          $control = $theType.( $theName );
        }
        else
        {
          Html::error('Class \'%s\' not found or not exist.', $theType );
        }
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
