<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form;

use SetBased\Html\Form\Control\CheckBoxesControl;
use SetBased\Html\Form\Control\ComplexControl;
use SetBased\Html\Form\Control\Control;
use SetBased\Html\Form\Control\FieldSet;
use SetBased\Html\Form\Control\HtmlControl;
use SetBased\Html\Form\Control\RadiosControl;
use SetBased\Html\Form\Control\SelectControl;
use SetBased\Html\Form\Control\SimpleControl;
use SetBased\Html\Form\Control\SpanControl;
use SetBased\Abc\Babel;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\Control\CoreFieldSet;
use SetBased\Abc\Core\Form\Validator\MandatoryValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Form class for all forms in the core of ABC.
 */
class CoreForm extends Form
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * FieldSet for all form control elements not of type "hidden".
   *
   * @var CoreFieldSet
   */
  protected $myVisibleFieldSet;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param bool $theCsrfCheckFlag If set the generated form has protection against CSRF.
   */
  public function __construct($theCsrfCheckFlag = true)
  {
    parent::__construct($theCsrfCheckFlag);

    $this->myVisibleFieldSet = parent::addFieldSet(new CoreFieldSet(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a button control to the visible fieldset of this form.
   *
   * @param string $theSubmitButtonText The text of the submit button.
   * @param null   $theResetButtonText  The text of the reset button. If null no reset button will be created.
   * @param string $theSubmitName       The name of the submit button.
   * @param string $theResetName        The name of the reset button.
   *
   * @return CoreButtonControl
   */
  public function addButtons($theSubmitButtonText = 'OK',
                             $theResetButtonText = null,
                             $theSubmitName = 'submit',
                             $theResetName = 'reset'
  )
  {
    return $this->myVisibleFieldSet->addButtons($theSubmitButtonText,
                                                $theResetButtonText,
                                                $theSubmitName,
                                                $theResetName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to the visible fieldset of this form control.
   *
   * @param Control $theControl       The from control
   * @param bool    $theWrdId         The wrd_id of the legend of the form control.
   * @param bool    $theMandatoryFlag If set the form control is mandatory.
   *
   * @return ComplexControl|SpanControl|ComplexControl|SimpleControl|SelectControl|CheckBoxesControl|RadiosControl
   */
  public function addFormControl($theControl, $theWrdId = null, $theMandatoryFlag = false)
  {
    $this->myVisibleFieldSet->addFormControl($theControl);

    if ($theWrdId)
    {
      $theControl->setAttribute('_abc_label', Babel::getWord($theWrdId));
    }

    if ($theMandatoryFlag)
    {
      $theControl->AddValidator(new MandatoryValidator(0));
    }

    return $theControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form control.
   *
   * @param string $theType          The type of the form control.
   * @param string $theName          The name of the form control.
   * @param int    $theWrdId         The wrd_id of the legend of the form control.
   * @param bool   $theMandatoryFlag If set the form control is mandatory.
   *
   * @return CheckBoxesControl|ComplexControl|RadiosControl|SelectControl|SimpleControl|SpanControl|HtmlControl
   */
  public function createFormControl($theType, $theName, $theWrdId = null, $theMandatoryFlag = false)
  {
    switch ($theType)
    {
      case 'hidden':
      case 'constant':
      case 'invisible':
        $ret = $this->myHiddenFieldSet->createFormControl($theType, $theName);
        break;

      default:
        // Add all other controls to the visible field set.
        $ret = $this->myVisibleFieldSet->createFormControl($theType, $theName, $theWrdId, $theMandatoryFlag);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the visible fieldset of this form.
   *
   * @return FieldSet|CoreFieldSet
   */
  public function getVisibleFieldSet()
  {
    return $this->myVisibleFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param int $theWrdId The wrd_id of the title.
   */
  public function setTitle($theWrdId)
  {
    $this->myVisibleFieldSet->setTitle(Babel::getWord($theWrdId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param string $theTitle The title.
   */
  public function setTitleText($theTitle)
  {
    $this->myVisibleFieldSet->setTitle($theTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
