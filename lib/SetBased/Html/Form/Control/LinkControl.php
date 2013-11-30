<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

/**
 * Class LinkControl
 *
 * @package SetBased\Html\Form\Control
 */
class LinkControl extends Control
{
  /**
   * @var string The inner HTML code of this div element.
   */
  protected $myInnerHtml;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $ret = $this->myPrefix;

    $ret .= '<a';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';

    $ret .= $this->myInnerHtml;

    $ret .= '</a>';

    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null;
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this a element.
   *
   * @param string $theHtmlSnippet The inner HTML. It is the developer's responsibility that it is valid HTML code.
   */
  public function setInnerHtml( $theHtmlSnippet )
  {
    $this->myInnerHtml = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner text of this a element.
   *
   * @param string $theText The inner text. This text will be converted to valid HTML code.
   */
  public function setInnerText( $theText )
  {
    $this->myInnerHtml = HTML::txt2Html( $theText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param null $theValues
   */
  public function setValuesBase( &$theValues )
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
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
