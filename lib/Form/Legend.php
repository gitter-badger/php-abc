<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Html;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for generation legend elements.
 */
class Legend
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The attributes of this legend.
   *
   * @var string[]
   */
  protected $myAttributes = [];

  /**
   * @var string The inner HTML snippet of this legend.
   */
  protected $myLegend;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this legend.
   *
   * @return string
   */
  public function generate()
  {
    return Html::generateElement('legend', $this->myAttributes, $this->myLegend, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute @a $theName of this attribute to @a $theValue.
   * * If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
   * * If @a $theName is 'class' the @a $theValue is appended to space separated list of classes (unless the above rule
   *   applies.)
   *
   * @param string $theName  The name of the attribute.
   * @param string $theValue The value for the attribute.
   */
  public function setAttribute($theName, $theValue)
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      unset($this->myAttributes[$theName]);
    }
    else
    {
      if ($theName=='class' && isset($this->myAttributes[$theName]))
      {
        $this->myAttributes[$theName] .= ' ';
        $this->myAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myAttributes[$theName] = $theValue;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the text of this legend.
   *
   * @param string $theHtmlSnippet The text of legend. It is the developer's responsibility that it is valid HTML code.
   */
  public function setLegendHtml($theHtmlSnippet)
  {
    $this->myLegend = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the text of this legend.
   *
   * @param string $theText The text of legend. This text will be converted to valid HTML code.
   */
  public function setLegendText($theText)
  {
    $this->myLegend = Html::txt2Html($theText);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
