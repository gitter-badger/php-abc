<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

/** @brief Class for form controls of type image.
 */
class ImageControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $this->myAttributes['type'] = 'image';
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $ret  = $this->myPrefix;
    $ret .= $this->generatePrefixLabel();

    $ret .= "<input";
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
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    /**
     * @todo Implement loadSubmittedValuesBase for control type image.
     */
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
