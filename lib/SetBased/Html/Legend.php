<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 *
 * @par Copyright:
 * Set Based IT Consultancy
 *
 * $Date: 2013/03/04 19:02:37 $
 *
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
class Legend
{
  protected $myAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  public function __construct()
  {
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** Helper function for Legend::setAttribute. Sets the value of attribute with name @a $theName of this form
      to @a $theValue. If @a $theValue is @c null, @c false, or @c '' the attribute is unset.
      @param $theName  The name of the attribute.
      @param $theValue The value for the attribute.

      @todo Document how attribute class is handled.
   */
  protected function setAttributeBase( $theName, $theValue )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myAttributes[$theName] );
    }
    else
    {
      if ($theName==='class' && isset($this->myAttributes[$theName]))
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
  public function setAttribute( $theName, $theValue, $theExtendedFlag=false )
  {
    switch ($theName)
    {
      // Advanced attributes.
    case 'accesskey':

      // Common core attributes.
    case 'class':
    case 'id':
    case 'title':

      // Common internationalization attributes.
    case 'xml:lang':
    case 'dir':

      // Common event attributes.
    case 'onclick':
    case 'ondblclick':
    case 'onmousedown':
    case 'onmouseup':
    case 'onmouseover':
    case 'onmousemove':
    case 'onmouseout':
    case 'onkeypress':
    case 'onkeydown':
    case 'onkeyup':

      // Common style attribute.
    case 'style':

      // H2O attributes.
    case 'set_inline':

      $this->setAttributeBase( $theName, $theValue );
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->setAttributeBase( $theName, $theValue );
      }
      else
      {
        Html::error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate()
  {
    $ret .= "<legend";

    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= Html::generateAttribute( $name, $value );
    }

    $ret .= '>';

    $ret .= $this->myAttributes['set_inline'];

    $ret .= "</legend>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
