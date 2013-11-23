<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
class SpanControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $ret  = $this->myPrefix;

    $ret .= '<span';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';

    if (!empty($this->myAttributes['set_html']))
    {
      $ret .= $this->myAttributes['set_html'];
    }

    $ret .= '</span>';
    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function validateBase( &$theInvalidFormControls )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setValuesBase( &$theValues )
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
