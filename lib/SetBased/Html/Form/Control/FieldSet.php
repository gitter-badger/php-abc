<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class FieldSet
 *
 * @package SetBased\Html\Form\Control
 */
class FieldSet extends ComplexControl
{
  /**
   * @var Html\Legend
   */
  protected $myLegend;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theType
   *
   * @return Html\Legend
   */
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
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $ret = $this->generateOpenTag();

    $ret .= $this->generateLegend();

    $ret .= parent::generate( $theParentName );

    $ret .= $this->generateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generateCloseTag()
  {
    $ret = "</fieldset>\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
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
  /**
   * @return string
   */
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
}

//----------------------------------------------------------------------------------------------------------------------
