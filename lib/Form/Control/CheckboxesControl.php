<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;
use SetBased\Html\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class CheckboxesControl
 *
 * @todo    Implement disabled hard (can not be changed via javascript) and disabled sort (can be changed via javascript).
 * @package SetBased\Html\Form\Control
 */
class CheckboxesControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string|null The key in $myOptions holding the checked flag for the checkboxes.
   */
  protected $myCheckedKey;

  /**
   * @var string|null The key in $myOptions holding the disabled flag for the checkboxes.
   */
  protected $myDisabledKey;

  /**
   * @var string|null The key in $myOptions holding the HTML ID attribute of the checkboxes.
   */
  protected $myIdKey;

  /**
   * @var string The key in $myOptions holding the keys for the checkboxes.
   */
  protected $myKeyKey;

  /**
   * @var string The key in $myOptions holding the labels for the checkboxes.
   */
  protected $myLabelKey;

  /**
   * @var string The HTML snippet appended after each label for the checkboxes.
   */
  protected $myLabelPostfix = '';

  /**
   * @var string The HTML snippet inserted before each label for the checkboxes.
   */
  protected $myLabelPrefix = '';

  /**
   * @var array[] The options of this select box.
   */
  protected $myOptions;

  /**
   * @var Obfuscator The obfuscator for the names of the checkboxes.
   */
  protected $myOptionsObfuscator;

  /**
   * @var string The value of the checked radio button.
   */
  protected $myValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $submit_name = $this->getSubmitName( $theParentName );

    $html = $this->myPrefix;

    $html .= '<div';
    foreach ($this->myAttributes as $name => $value)
    {
      // Ignore attributes starting with an underscore.
      if ($name[0]!='_') $html .= Html::generateAttribute( $name, $value );
    }
    $html .= '>';

    if (is_array( $this->myOptions ))
    {
      foreach ($this->myOptions as $option)
      {
        $code = ($this->myOptionsObfuscator) ?
          $this->myOptionsObfuscator->encode( $option[$this->myKeyKey] ) : $option[$this->myKeyKey];

        $key = ($this->myIdKey && isset($option[$this->myIdKey])) ? $key = $option[$this->myIdKey] : Html::getAutoId();

        $html .= '<input type="checkbox"';

        if ($submit_name!=='') $html .= Html::generateAttribute( 'name', "${submit_name}[$code]" );
        else                   $html .= Html::generateAttribute( 'name', $code );

        $html .= Html::generateAttribute( 'id', $key );

        if ($this->myCheckedKey && isset($option[$this->myCheckedKey]))
        {
          $html .= Html::generateAttribute( 'checked', $option[$this->myCheckedKey] );
        }

        if ($this->myDisabledKey && isset($option[$this->myDisabledKey]))
        {
          $html .= Html::generateAttribute( 'disabled', $option[$this->myDisabledKey] );
        }

        $html .= '/>';

        $html .= $this->myLabelPrefix;
        $html .= '<label';
        $html .= Html::generateAttribute( 'for', $key );
        $html .= '>';
        $html .= Html::txt2Html( $option[$this->myLabelKey] );
        $html .= '</label>';
        $html .= $this->myLabelPostfix;
      }
    }

    $html .= '</div>';

    $html .= $this->myPostfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of check radio button the values with the name of this form control as key.
   *
   * @param array $theValues The values.
   */
  public function getSetValuesBase( &$theValues )
  {
    if ($this->myName==='')
    {
      $tmp = &$theValues;
    }
    else
    {
      $theValues[$this->myName] = array();
      $tmp                      = &$theValues[$this->myName];
    }

    foreach ($this->myOptions as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->myKeyKey];

      // Get the original value (i.e. the option is checked or not).
      $tmp[$key] = (!empty($option[$this->myCheckedKey]));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of the check radio button.
   *
   * @return string
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control according to @a $theValues.
   *
   * @param array $theValues
   */
  public function mergeValuesBase( $theValues )
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
      foreach ($this->myOptions as $id => $option)
      {
        if (array_key_exists( $option[$this->myKeyKey], $values ))
        {
          $this->myOptions[$id][$this->myCheckedKey] = !empty($values[$option[$this->myKeyKey]]);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is inserted before the HTML code of each label of the checkboxes to @a $theHtmlSnippet.
   *
   * @param string $theHtmlSnippet
   */
  public function setLabelPostfix( $theHtmlSnippet )
  {
    $this->myLabelPostfix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is appended after the HTML code of each label of the checkboxes to @a $theHtmlSnippet.
   *
   * @param string $theHtmlSnippet
   */
  public function setLabelPrefix( $theHtmlSnippet )
  {
    $this->myLabelPrefix = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]     $theOptions      An array of arrays with the options.
   * @param string      $theKeyKey       The key holding the keys of the checkboxes.
   * @param string      $theLabelKey     The key holding the labels for the checkboxes.
   * @param string|null $theCheckedKey   The key holding the checked flag. Any none empty value results that the
   *                                     checkbox is checked.
   * @param string|null $theDisabledKey  The key holding the disabled flag. Any none empty value results that the
   *                                     checkbox is disabled.
   * @param string|null $theIdKey        The key holding the HTML ID attribute of the checkboxes.
   */
  public function setOptions( &$theOptions,
                              $theKeyKey,
                              $theLabelKey,
                              $theCheckedKey = 'set_map_checked',
                              $theDisabledKey = null,
                              $theIdKey = null
  )
  {
    $this->myOptions     = $theOptions;
    $this->myKeyKey      = $theKeyKey;
    $this->myLabelKey    = $theLabelKey;
    $this->myCheckedKey  = $theCheckedKey;
    $this->myDisabledKey = $theDisabledKey;
    $this->myIdKey       = $theIdKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the obfuscator for the names (most likely the names are databases ID's) of the checkboxes.
   *
   * @param Obfuscator $theObfuscator The obfuscator for the checkboxes.
   */
  public function setOptionsObfuscator( $theObfuscator )
  {
    $this->myOptionsObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control according to @a $theValues.
   *
   * @param array $theValues
   */
  public function setValuesBase( $theValues )
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

    foreach ($this->myOptions as $id => $option)
    {
      $this->myOptions[$id][$this->myCheckedKey] = !empty($values[$option[$this->myKeyKey]]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    foreach ($this->myOptions as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->myKeyKey];

      // If an obfuscator is installed compute the obfuscated code of the (database) ID.
      $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode( $key ) : $key;

      // Get the original value (i.e. the option is checked or not).
      $value = (isset($option[$this->myCheckedKey])) ? $option[$this->myCheckedKey] : false;

      if ($submit_name!=='')
      {
        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$submit_name][$code])) ? $theSubmittedValue[$submit_name][$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$this->myName][$key] = $this;

        // Set the white listed value.
        $theWhiteListValue[$this->myName][$key] = !empty($submitted);

      }
      else
      {
        // Get the submitted value (i.e. the option is checked or not).
        $submitted = (isset($theSubmittedValue[$code])) ? $theSubmittedValue[$code] : false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($submitted)) $theChangedInputs[$key] = $this;

        // Set the white listed value.
        $theWhiteListValue[$key] = !empty($submitted);
      }

      // Set the submitted value to be used method getSubmittedValue.
      $this->myValue[$key] = !empty($submitted);

      $this->myOptions[$i][$this->myCheckedKey] = !empty($submitted);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate( $this );
      if ($valid!==true)
      {
        $theInvalidFormControls[$this->myName] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
