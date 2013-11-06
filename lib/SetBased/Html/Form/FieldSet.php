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
namespace SetBased\Html\Form;
use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
class FieldSet extends ComplexControl
{
  protected $myLegend;

  //--------------------------------------------------------------------------------------------------------------------
  public function createLegend( $theType='legend' )
  {
    switch ($theType)
    {
    case 'legend':
      $tmp = new \SetBased\Html\Legend();
      break;

    default:
      $tmp = new $theType();
    }

    $this->myLegend = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateOpenTag()
  {
    $ret = '<fieldset';
    foreach( $this->myAttributes as $name => $value )
    {
      $ret .= SetBased\Html\Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateLegend()
  {
    if ($this->myLegend) $ret = $this->myLegend->generate();
    else                 $ret = false;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateCloseTag()
  {
    $ret = "</fieldset>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $ret  = $this->generateOpenTag();

    $ret .= $this->generateLegend();

    $ret .= parent::generate( $theParentName );

    $ret .= $this->generateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}



//----------------------------------------------------------------------------------------------------------------------
