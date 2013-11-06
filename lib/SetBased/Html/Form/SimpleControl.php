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
    if ($this->myName===false) SetBased\Html\Html::error( 'Name is emtpy' );
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function setLabelAttribute( $theName, $theValue, $theExtendedFlag=false  )
  {
    if ($theValue===null ||$theValue===false ||$theValue==='')
    {
      unset( $this->myLabelAttributes[$theName] );
    }
    else
    {
      if ($theName==='class' && isset($this->myLabelAttributes[$theName]))
      {
        $this->myLabelAttributes[$theName] .= ' ';
        $this->myLabelAttributes[$theName] .= $theValue;
      }
      else
      {
        $this->myLabelAttributes[$theName] = $theValue;
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
      $ret .= SetBased\Html\Html::generateAttribute( $name, $value );
    }
    $ret .= ">";

    $ret .= $this->myLabelAttributes['set_label'];
    $ret .= '</label>';

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
        $theInvalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
