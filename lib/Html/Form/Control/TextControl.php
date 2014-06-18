<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class TextControl
 * Class for form controls of type input:text.
 *
 * @package SetBased\Html\Form\Control
 */
class TextControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // By default whitespace is pruned from text form controls.
    $this->myCleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @note Before generation the following HTML attributes are overwritten:
   *       * name    Will be replaced with the submit name of this form control.
   *       * type    Will be replaced with 'test'.
   *       * value   Will be replaced with (formatted) @c $myValue.
   *       * size    Will be replaced with minimum of attribute 'size' (if set) and attribute 'maxlength' (if set).
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type']  = 'text';
    $this->myAttributes['name']  = $this->getSubmitName( $theParentName );

    if ($this->myFormatter) $this->myAttributes['value'] = $this->myFormatter->format( $this->myValue );
    else                    $this->myAttributes['value'] = $this->myValue;

    if (isset($this->myAttributes['maxlength']))
    {
      if (isset($this->myAttributes['size']))
      {
        $this->myAttributes['size'] = min( $this->myAttributes['size'], $this->myAttributes['maxlength'] );
      }
      else
      {
        $this->myAttributes['size'] = $this->myAttributes['maxlength'];
      }
    }

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();

    $ret .= '<input';
    foreach ($this->myAttributes as $name => $value)
    {
      // Ignore attributes starting with an underscore.
      if ($name[0]!='_') $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';

    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
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

    // Get the submitted value and cleaned (if required).
    $new_value = (isset($theSubmittedValue[$submit_name])) ? $theSubmittedValue[$submit_name] : null;
    if ($this->myCleaner) $new_value = $this->myCleaner->clean( $new_value );

    if ((string)$this->myValue!==(string)$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myValue                   = $new_value;
    }

    // The user can enter any text in a input:text box. So, any value is white listed.
    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
