<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;
use SetBased\Html\Obfuscator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SelectControl
 *
 * @package SetBased\Html\Form\Control
 */
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The key in $myOptions holding the disabled flag for the options in this select box.
   */
  protected $myDisabledKey;

  /**
   * @var string The key in $myOptions holding the keys for the options in this select box.
   */
  protected $myKeyKey;

  /**
   * @var string The key in $myOptions holding the labels for the options in this select box.
   */
  protected $myLabelKey;

  /**
   * @var array[] The options of this select box.
   */
  protected $myOptions;

  /**
   * @var Obfuscator The obfuscator for the names of the checkboxes.
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
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret = $this->myPrefix;

    $ret .= '<select';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";


    if (!empty($this->myAttributes['set_empty_option']))
    {
      $ret .= "<option value=' '></option>\n";
    }

    if (is_array( $this->myOptions ))
    {
      foreach ($this->myOptions as $option)
      {
        // Get the (database) ID of the option.
        $id = (string)$option[$this->myKeyKey];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($this->$myOptionsObfuscator) ? $this->$myOptionsObfuscator->encode( $id ) : $id;

        //
        $ret .= '<option';
        $ret .= Html::generateAttribute( 'value', $code );

        if (isset($this->myAttributes['set_value']) && $this->myAttributes['set_value']===$id)
        {
          $ret .= " selected='selected'";
        }

        if (isset($this->myDisabledKey) && !empty($option[$this->myDisabledKey]))
        {
          $ret .= " disabled='disabled'";
        }

        $ret .= '>';
        $ret .= Html::txt2Html( $option[$this->myLabelKey] );
        $ret .= "</option>\n";
      }
    }

    $ret .= '</select>';
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]     $theOptions      An array of arrays with the options.
   * @param string      $theKeyKey       The key holding the keys of the options.
   * @param string      $theLabelKey     The key holding the labels for the options.
   * @param string|null $theDisabledKey  The key holding the disabled flag. Any none empty value results that the
   *                                     option is disables.
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
   * @param array $theValues
   */
  public function setValuesBase( &$theValues )
  {
    /** @todo check on type and value is in list of options. */
    $this->myAttributes['set_value'] = (string)$theValues[$this->myName];
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

    // Normalize default value as a string.
    $value = isset($this->myAttributes['set_value']) ? (string)$this->myAttributes['set_value'] : '';

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted = (string)$theSubmittedValue[$submit_name];

      if (empty($this->myAttributes['set_empty_option']) && $submitted===' ')
      {
        $theWhiteListValue[$this->myName] = null;
        if ($value!=='' && $value!==' ')
        {
          $theChangedInputs[$this->myName] = $this;
        }
      }
      else
      {
        if (is_array( $this->myOptions ))
        {
          foreach ($this->myOptions as $option)
          {
            // Get the key of the option.
            $id = (string)$option[$this->myKeyKey];

            // If an obfuscator is installed compute the obfuscated code of the (database) ID.
            $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode( $id ) : $id;

            if ($submitted===(string)$code)
            {
              // If the original value differs from the submitted value then the form control has been changed.
              if ($value!==$id)
              {
                $theChangedInputs[$this->myName] = $this;
              }

              // Set the white listed value.
              $this->myAttributes['set_value']  = $id;
              $theWhiteListValue[$this->myName] = $id;

              // Leave the loop.
              break;
            }
          }
        }
      }
    }
    else
    {
      // No value has been submitted.
      $theWhiteListValue[$this->myName] = null;
      if ($value!=='' && $value!==' ')
      {
        $theChangedInputs[$this->myName] = $this;
      }
    }

    if (!array_key_exists( $this->myName, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $theWhiteListValue[$this->myName] = isset($this->myAttributes['set_value']) ? $this->myAttributes['set_value'] : null;
    }

    // Set the submitted value to be used method GetSubmittedValue.
    $this->myAttributes['set_submitted_value'] = $theWhiteListValue[$this->myName];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
