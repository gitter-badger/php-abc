<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SimpleControl
 *
 * Class for generating form control elements of type
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
 *
 * @package SetBased\Html\Form\Control
 */
abstract class SimpleControl extends Control
{
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   *
   * @var \SetBased\Html\Form\Cleaner\Cleaner
   */
  protected $myCleaner;

  /**
   * @var array
   */
  protected $myLabelAttributes = array();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a SimpleControl object for generating a form control element of @a $theType with (local)
   * name \a $theName.
   *
   * @param string $theName
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // A simple form control must have a name.
    if ($this->myName==='')
    {
      Html::error( 'Name is empty' );
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the cleaner for this form control.
   *
   * @param \SetBased\Html\Form\Cleaner\Cleaner $theCleaner The cleaner for this form control/
   */
  public function setCleaner( $theCleaner )
  {
    $this->myCleaner = $theCleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   * @param string $theValue
   */
  public function setLabelAttribute( $theName, $theValue )
  {
    if ($theValue==='' || $theValue===null || $theValue===false)
    {
      unset($this->myLabelAttributes[$theName]);
    }
    else
    {
      if ($theName=='class' && isset($this->myLabelAttributes[$theName]))
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
  /**
   * @return string
   */
  protected function generateLabel()
  {
    $ret = '<label';

    foreach ($this->myLabelAttributes as $name => $value)
    {
      $ret .= Html::generateAttribute( $name, $value );
    }
    $ret .= '>';

    $ret .= $this->myLabelAttributes['set_label'];
    $ret .= '</label>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return string
   */
  protected function generatePostfixLabel()
  {
    if (isset($this->myLabelAttributes['set_position']) && $this->myLabelAttributes['set_position']=='postfix')
    {
      $ret = $this->generateLabel();
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
  protected function generatePrefixLabel()
  {
    $ret = '';

    if (isset($this->myLabelAttributes['set_position']))
    {
      if (!isset($this->myAttributes['id']))
      {
        $id                             = Html::getAutoId();
        $this->myAttributes['id']       = $id;
        $this->myLabelAttributes['for'] = $id;
      }
      else
      {
        $this->myLabelAttributes['for'] = $this->myAttributes['id'];
      }

      if ($this->myLabelAttributes['set_position']=='prefix')
      {
        $ret .= $this->generateLabel();
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theInvalidFormControls
   *
   * @return bool
   */
  protected function validateBase( &$theInvalidFormControls )
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
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
