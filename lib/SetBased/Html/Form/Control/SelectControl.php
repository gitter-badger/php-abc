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


  /**
   * If set the first option in the select box with be an option with an empty label with value $myOptionEmpty.
   *
   * @var string The value for the empty option.
   * */
  protected $myEmptyOption;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $html = $this->myPrefix;

    $html .= '<select';
    foreach ($this->myAttributes as $name => $value)
    {
      $html .= Html::generateAttribute( $name, $value );
    }
    $html .= ">\n";


    // Add an empty option, if necessary.
    if (!isset($this->myEmptyOption))
    {
      $html .= '<option';
      $html .= Html::generateAttribute( 'value', $this->myEmptyOption );
      $html .= "</option>\n";
    }

    if (is_array( $this->myOptions ))
    {
      foreach ($this->myOptions as $option)
      {
        // Get the (database) ID of the option.
        $id = (string)$option[$this->myKeyKey];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($this->myOptionsObfuscator) ? $this->myOptionsObfuscator->encode( $id ) : $id;

        //
        $html .= '<option';
        $html .= Html::generateAttribute( 'value', $code );

        if ((string)$this->myValue===$id)
        {
          $html .= " selected='selected'";
        }

        if (isset($this->myDisabledKey) && !empty($option[$this->myDisabledKey]))
        {
          $html .= " disabled='disabled'";
        }

        $html .= '>';
        $html .= Html::txt2Html( $option[$this->myLabelKey] );
        $html .= "</option>\n";
      }
    }

    $html .= '</select>';
    $html .= $this->myPostfix;

    return $html;
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
   * Adds an option with empty label as first option to this select box.
   *
   * @param string $theEmptyOption The value for the empty option.
   */
  public function setEmptyOption( $theEmptyOption )
  {
    $this->myEmptyOption = $theEmptyOption;
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
    $value = (string)$this->myValue;

    if (isset($theSubmittedValue[$submit_name]))
    {
      // Normalize the submitted value as a string.
      $submitted = (string)$theSubmittedValue[$submit_name];

      if (isset($this->myEmptyOption) && $submitted===(string)$this->myEmptyOption)
      {

        $this->myValue                    = null;
        $theWhiteListValue[$this->myName] = null;
        if ($value!==(string)$this->myEmptyOption)
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
              $this->myValue                    = $id;
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
      $this->myValue                    = null;
      $theWhiteListValue[$this->myName] = null;
      if ($value!==(string)$this->myEmptyOption)
      {
        $theChangedInputs[$this->myName] = $this;
      }
    }

    if (!array_key_exists( $this->myName, $theWhiteListValue ))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      /** @todo validate the default value is a white list value. */
      $theWhiteListValue[$this->myName] = $this->myValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
