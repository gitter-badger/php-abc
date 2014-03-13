<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SimpleControl
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
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var \SetBased\Html\Form\Formatter\Formatter
   */
  protected $myFormatter;

  /**
   * The label of this form control.
   *
   * @var string A HTML snippet.
   */
  protected $myLabel;

  /**
   * The attributes for the label of this form control.
   *
   * @var string[]
   */
  protected $myLabelAttributes = array();

  /**
   * The position of the label of this form control.
   * * 'pre'  The label will be inserted before the HML code of this form control.
   * * 'post' The label will be appended after the HML code of this form control.
   *
   * @var 'pre'|'post'|null
   */
  protected $myLabelPosition;

  /**
   * @var string The value of this form control.
   */
  protected $myValue;

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
   * Returns the submitted value of this form control.
   *
   * @return string
   */
  public function getSubmittedValue()
  {
    return $this->myValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param \SetBased\Html\Form\Cleaner\Cleaner|null $theCleaner The cleaner for this form control.
   */
  public function setCleaner( $theCleaner )
  {
    $this->myCleaner = $theCleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the formatter for this form control.
   *
   * @param \SetBased\Html\Form\Formatter\Formatter|null $theFormatter The formatter for this form control.
   */
  public function setFormatter( $theFormatter )
  {
    $this->myFormatter = $theFormatter;
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
   * Sets label for this form control.
   *
   * @param string $theHtmlSnippet The (inner) label HTML snippet. It is the developer's responsibility that it is valid HTML code.
   */
  public function setLabelHtml( $theHtmlSnippet )
  {
    $this->myLabel = $theHtmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Sets the position of the label of this form control.
   * * 'pre'  The label will be inserted before the HML code of this form control.
   * * 'post' The label will be appended after the HML code of this form control.
   * * 'null' No label will be generated for this form control.
   *
   * @param 'pre'|'post'|null $thePosition
   */
  public function setLabelPosition( $thePosition )
  {
    $this->myLabelPosition = $thePosition;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets label for this form control.
   *
   * @param string $theText The (inner) label text. This text will be converted to valid HTML code.
   */
  public function setLabelText( $theText )
  {
    $this->myLabel = HTML::txt2Html( $theText );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string $theValue The new value for the form control.
   */
  public function setValue( $theValue )
  {
    $this->myValue = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theValues
   */
  public function setValuesBase( &$theValues )
  {
    $this->setValue( isset($theValues[$this->myName]) ? $theValues[$this->myName] : null );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the HTML code for the label for this form control.
   *
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

    $ret .= $this->myLabel;
    $ret .= '</label>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for a label for this form control to be appended after the HTML code of this form control.
   *
   * @return string
   */
  protected function generatePostfixLabel()
  {
    // Generate a postfix label, if required.
    if ($this->myLabelPosition=='post')
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
   * Returns HTML code for a label for this form control te be inserted before the HTML code of this form control.
   *
   * @return string
   */
  protected function generatePrefixLabel()
  {
    // If a label must be generated make sure the form control and the label have matching 'id' and 'for' attributes.
    if (isset($this->myLabelPosition))
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
    }

    // Generate a prefix label, if required.
    if ($this->myLabelPosition=='pre')
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
