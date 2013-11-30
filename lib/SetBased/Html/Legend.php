<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class Legend
 *
 * @package SetBased\Html
 */
class Legend
{
  /**
   * The attributes of this legend.
   *
   * @var string[]
   */
  protected $myAttributes = array();

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
   * Set the text of this legend.
   *
   * @param string $theHtmlSnippet The text of legend. It is the developer's responsibility that it is valid HTML code.
   */
  public function SetLegendHtml( $theHtmlSnippet )
  {
    $this->myLegend = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the text of this legend.
   *
   * @param string $theText The text of legend. This text will be converted to valid HTML code.
   */
  public function SetLegendText( $theText )
  {
    $this->myLegend = HTML::txt2Html( $theText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this legend.
   *
   * @return string
   */
  public function generate()
  {

    $html = '<legend';
    foreach ($this->myAttributes as $name => $value)
    {
      $html .= Html::generateAttribute( $name, $value );
    }
    $html .= '>';
    $html .= $this->myLegend;
    $html .= "</legend>\n";

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of attribute @a $theName of this attribute to @a $theValue.
   * * If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
   * * If @a $theName is 'class' the @a $theValue is appended to space separated list of classes (unless the above rule
   *   applies.)
   *
   * @param string      $theName  The name of the attribute.
   * @param string|null $theValue The value for the attribute.
   */
  public function setAttribute( $theName, $theValue )
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
}

//----------------------------------------------------------------------------------------------------------------------
