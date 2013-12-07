<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class ImageControl
 * Class for form controls of type image.
 *
 * @package SetBased\Html\Form\Control
 */
class ImageControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'image';

    // For images we use local names. It is the task of the developer to ensure the local names of buttons
    // are unique.
    $this->myAttributes['name'] = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    $ret = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();

    $ret .= '<input';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '/>';

    $ret .= $this->generatePostfixLabel();
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string|bool $theValue.
   */
  public function setValue( $theValue )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    /**
     * @todo Implement loadSubmittedValuesBase for control type image.
     */
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
