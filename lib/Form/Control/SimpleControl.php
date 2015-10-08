<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Error\LogicException;
use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Formatter\Formatter;
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for generating form control elements of type:
 * * text
 * * password
 * * hidden
 * * checkbox
 * * radio
 * * submit
 * * reset
 * * button
 * * file
 * * image
 */
abstract class SimpleControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   *
   * @var Cleaner
   */
  protected $myCleaner;

  /**
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var Formatter
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
  protected $myLabelAttributes = [];

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
   * @param string $theName The name of the form control.
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    // A simple form control must have a name.
    if ($this->myName==='')
    {
      throw new LogicException('Name is empty');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of this form control to the values.
   *
   * @param array $theValues
   */
  public function getSetValuesBase(&$theValues)
  {
    $theValues[$this->myName] = $this->myValue;
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
   * @param array $theValues
   */
  public function mergeValuesBase($theValues)
  {
    if (array_key_exists($this->myName, $theValues))
    {
      $this->setValuesBase($theValues);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner $theCleaner The cleaner.
   */
  public function setCleaner($theCleaner)
  {
    $this->myCleaner = $theCleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the formatter for this form control.
   *
   * @param Formatter $theFormatter The formatter.
   */
  public function setFormatter($theFormatter)
  {
    $this->myFormatter = $theFormatter;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of an attribute the label for this form control.
   *
   * The attribute is unset when the value is one of:
   * * null
   * * false
   * * ''.
   *
   * If attribute name is 'class' then the value is appended to the space separated list of classes.
   *
   * @param string $theName  The name of the attribute.
   * @param string $theValue The value for the attribute.
   */
  public function setLabelAttribute($theName, $theValue)
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
   * Sets the inner HTML of the label for this form control.
   *
   * @param string $theHtmlSnippet The (inner) label HTML snippet. It is the developer's responsibility that it is
   *                               valid HTML code.
   */
  public function setLabelHtml($theHtmlSnippet)
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
  public function setLabelPosition($thePosition)
  {
    $this->myLabelPosition = $thePosition;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the abel for this form control.
   *
   * @param string $theText The (inner) label text special characters are converted to HTML entities.
   */
  public function setLabelText($theText)
  {
    $this->myLabel = HTML::txt2Html($theText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string $theValue The new value for the form control.
   */
  public function setValue($theValue)
  {
    $this->myValue = $theValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function setValuesBase($theValues)
  {
    $this->setValue(isset($theValues[$this->myName]) ? $theValues[$this->myName] : null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for the label for this form control.
   *
   * @return string
   */
  protected function generateLabel()
  {
    return Html::generateElement('label', $this->myLabelAttributes, $this->myLabel, true);
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
   * {@inheritdoc}
   */
  protected function validateBase(&$theInvalidFormControls)
  {
    $valid = true;

    foreach ($this->myValidators as $validator)
    {
      $valid = $validator->validate($this);
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
