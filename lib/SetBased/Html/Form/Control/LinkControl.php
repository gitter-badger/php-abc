<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

class LinkControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $ret = $this->myPrefix;

    $ret .= '<a';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';
    if (!empty($this->myAttributes['set_html']))
    {
      $ret .= $this->myAttributes['set_html'];
    }
    $ret .= '</a>';

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
