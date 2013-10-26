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
/** Class for generating form control elements of type
  * \li text
  * \li password
  * \li hidden
  * \li checkbox
  * \li radio
  * \li submit
  * \li reset
  * \li button
  * \li file
  * \li image
  */

abstract class SimpleControl extends Control
{
  protected $myLabelAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  /** Creates a SimpleControl object for generating a form control element of @a $theType with (local)
    * name \a $theName.
    */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // A simple form control must have a name.
    $local_name = $this->myAttributes['name'];
    if ($local_name===false) SetBased\Html\Html::error( 'Name is emtpy' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setLabelAttribute( $theName, $theValue, $theExtendedFlag=false  )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myLabelAttributes[$theName] );
      return;
    }

    switch ($theName)
    {
      // Basic attributes.
    // case 'for':

      // Advanced attributes.
    case 'accesskey':
    case 'onblur':
    case 'onfocus':

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

      // H2O Attributes
    case 'set_label':
    case 'set_position':
    case 'set_prefix':
    case 'set_postfix':

      if ($theName==='class' && isset($this->myLabelAttributes[$theName]))
      {
        $this->myLabelAttributes[$theName] .= ' ';
        $this->myLabelAttributes[$theName] .= (string)$theValue;
      }
      else
      {
        $this->myLabelAttributes[$theName] = (string)$theValue;
      }
      break;

    default:
      if ($theExtendedFlag)
      {
        $this->myLabelAttributes[$theName] = (string)$theValue;
      }
      else
      {
        SetBased\Html\Html::error( "Unsupported attribute '%s'.", $theName );
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generatePrefixLabel()
  {
    $ret = false;

    if (isset($this->myLabelAttributes['set_position']))
    {
      if ($this->myAttributes['id']=='')
      {
        $id = SetBased\Html\Html::getAutoId();
        $this->myAttributes['id']       = $id;
        $this->myLabelAttributes['for'] = $id;
      }
      else
      {
        $this->myLabelAttributes['for'] = $this->myAttributes['id'];
      }

      if ($this->myLabelAttributes['set_position']=='prefix') $ret .= $this->generateLabel();
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generatePostfixLabel()
  {
    if (isset($this->myLabelAttributes['set_position']) && $this->myLabelAttributes['set_position']=='postfix')
    {
      $ret = $this->generateLabel();
    }
    else
    {
      $ret = false;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function generateLabel()
  {
    $ret  = (isset($this->myAttributes['set_prefix'])) ? $this->myAttributes['set_prefix'] : '';
    $ret .= '<label';

    foreach( $this->myLabelAttributes as $index => $value )
    {
      switch ($index)
      {
       // Common core attributes
      case 'for':
        if ($value!='') $ret .= " $index='$value'";
        break;


      case 'class':
      case 'id':
      case 'title':
        if ($value!='') $ret .= " $index='$value'";
        break;


      case 'xml:lang':
      case 'dir':
        if ($value!='') $ret .= " $index='$value'";
        break;


      // Common event attributes
      case 'onclick':
      case 'ondbclick':
      case 'onmousedown':
      case 'onmouseup':
      case 'onmouseover':
      case 'onmouseout':
      case 'onkeypress':
      case 'ononkeydown':
      case 'onkeyup':
        return SetBased\Html\Html::error( 'not implemented' );
        break;


      // Common style attribute
      case 'style':
        if ($value!='') $ret .= " $index='$value'";
        break;
      }
    }
    $ret .= ">";

    $ret .= $this->myLabelAttributes['set_label'];
    $ret .= "</label>";

    $ret .= $this->myLabelAttributes['set_postfix']."\n";

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function validateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach( $this->myValidators as $validator )
    {
      $valid = $validator->validate( $this );
      if ($valid!==true)
      {
        $local_name = $this->myAttributes['name'];
        $theInvalidFormControls[$local_name] = true;

        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
