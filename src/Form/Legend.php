<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Html;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Misc\HtmlElement;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for [legend](http://www.w3schools.com/tags/tag_legend.asp) elements.
 */
class Legend extends HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var string The inner HTML snippet of this legend.
   */
  protected $myLegend;

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
