<?php
//----------------------------------------------------------------------------------------------------------------------
/** @author Paul Water
 * @par Copyright:
 * Set Based IT Consultancy
 * $Date: 2013/03/04 19:02:37 $
 * $Revision:  $
 */
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form;

use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
class FieldSet extends ComplexControl
{
  /**
   * @var Html\Legend
   */
  protected $myLegend;

  //--------------------------------------------------------------------------------------------------------------------
  public function createLegend( $theType = 'legend' )
  {
    switch ($theType)
    {
      case 'legend':
        $tmp = new Html\Legend();
        break;

      default:
        $tmp = new $theType();
    }

    $this->myLegend = $tmp;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function generate( $theParentName )
  {
    $ret = $this->generateOpenTag();

    $ret .= $this->generateLegend();

    $ret .= parent::generate( $theParentName );

    $ret .= $this->generateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateOpenTag()
  {
    $ret = '<fieldset';
    foreach ($this->myAttributes as $name => $value)
    {
      $ret .= Html\Html::generateAttribute( $name, $value );
    }
    $ret .= ">\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateLegend()
  {
    if ($this->myLegend)
    {
      $ret = $this->myLegend->generate();
    }
    else
    {
      $ret = '';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateCloseTag()
  {
    $ret = "</fieldset>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
