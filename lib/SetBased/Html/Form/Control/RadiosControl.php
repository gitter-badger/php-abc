<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;
use SetBased\Html\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class RadiosControl
 *
 * @package SetBased\Html\Form\Control
 */
class RadiosControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The key in $myOptions holding the disabled flag for the radio buttons.
   */
  protected $myDisabledKey;

  /**
   * @var string The key in $myOptions holding the keys for the radio buttons.
   */
  protected $myKeyKey;

  /**
   * @var string The key in $myOptions holding the labels for the radio buttons.
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
   * @var Obfuscator The obfuscator for the names of the radio buttons.
   */
  protected $myOptionsObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $html = $this->myPrefix;

    $html .= '<div';
    foreach ($this->myAttributes as $name => $value)
    {
      $html .= Html::generateAttribute( $name, $value );
    }
    $html .= ">\n";

    if (is_array( $this->myOptions ))
    {
      $submit_name = $this->getSubmitName( $theParentName );
      foreach ($this->myOptions as $option)
      {
        $key = (string)$option[$this->myKeyKey];

        $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode( $key ) : $key;

        $id = Html::getAutoId();

        $html .= "<input type='radio' id='$id'";

        $html .= Html::generateAttribute( 'name', $submit_name );

        $html .= Html::generateAttribute( 'value', $code );

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$key)
        {
          $html .= " checked='checked'";
        }

        if ($this->myDisabledKey && !empty($option[$this->myDisabledKey]))
        {
          $html .= " disabled='disabled'";
        }

        $html .= '/>';

        $html .= $this->myLabelPrefix;
        $html .= '<label';
        $html .= Html::generateAttribute( 'for', $id );
        $html .= '>';
        $html .= Html::txt2Html( $option[$this->myLabelKey] );
        $html .= '</label>';
        $html .= $this->myLabelPostfix;

        $html .= "\n";
      }
    }

    $html .= "</div>";
    $html .= $this->myPostfix;

    return $html;
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
   * @param string      $theKeyKey       The key holding the keys of the radio buttons.
   * @param string      $theLabelKey     The key holding the labels for the radio buttons..
   * @param string|null $theDisabledKey  The key holding the disabled flag. Any none empty value results that the
   *                                     radio button is disabled.
   */
  public function setOptions( &$theOptions, $theKeyKey, $theLabelKey, $theDisabledKey = null )
  {
    $this->myOptions     = $theOptions;
    $this->myKeyKey      = $theKeyKey;
    $this->myLabelKey    = $theLabelKey;
    $this->myDisabledKey = $theDisabledKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the obfuscator for the names (most likely the names are databases ID's) of the radio buttons.
   *
   * @param Obfuscator $theObfuscator The obfuscator for the radio buttons.
   */
  public function setOptionsObfuscator( $theObfuscator )
  {
    $this->myOptionsObfuscator = $theObfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theValues
   */
  public function setValuesBase( &$theValues )
  {
    $this->myAttributes['set_value'] = $theValues[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted_value = (string)$theSubmittedValue[$submit_name];

      foreach ($this->myOptions as $option)
      {
        // Get the (database) ID of the option.
        $id = (string)$option[$this->myKeyKey];

        // If an obfuscator is installed compute the obfuscated code of the radio button name.
        $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode( $id ) : $id;

        if ($submitted_value===(string)$code)
        {
          // If the original value differs from the submitted value then the form control has been changed.
          if (!isset($this->myAttributes['set_value']) || $this->myAttributes['set_value']!==$id)
          {
            $theChangedInputs[$this->myName] = $this;
          }

          // Set the white listed value.
          $theWhiteListValue[$this->myName] = $id;
          $this->myAttributes['set_value']  = $id;

          // Leave the loop.
          break;
        }
      }
    }
    else
    {
      // No radio button has been checked.
      $theWhiteListValue[$this->myName] = null;
      $this->myAttributes['set_value']  = null;
    }

    if (!array_key_exists( $this->myName, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $theWhiteListValue[$this->myName] = (isset($this->myAttributes['set_value'])) ? $this->myAttributes['set_value'] : null;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase( &$theInvalidFormControls )
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
