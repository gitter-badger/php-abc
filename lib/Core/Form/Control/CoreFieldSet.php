<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\Control;

use SetBased\Abc\Babel;
use SetBased\Abc\Core\Form\Validator\MandatoryValidator;
use SetBased\Abc\Helper\Html;
use SetBased\Html\Form\Control\FieldSet;
use SetBased\Html\Form\Control\TextControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Fieldset for visible form controls in core form.
 */
class CoreFieldSet extends FieldSet
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The complex form control holding the buttons of this fieldset.
   *
   * @var CoreButtonControl
   */
  private $myButtonFormControl;

  /**
   * The title of the in the header of the form of this field set.
   *
   * @var string
   */
  private $myHtmlTitle;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds buttons at the bottom to this form.
   *
   * @param CoreButtonControl $theControl For control with buttons.
   *
   * @return CoreButtonControl
   */
  public function addButtonControl($theControl)
  {
    $this->myButtonFormControl = $theControl;
    $ret                       = $this->addFormControl($this->myButtonFormControl);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a button control to this fieldset.
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
    $this->myButtonFormControl = new CoreButtonControl('');
    $ret                       = $this->addFormControl($this->myButtonFormControl);

    $submit = $ret->createFormControl('submit', $theSubmitName);
    $submit->setValue($theSubmitButtonText);

    if ($theResetButtonText)
    {
      $reset = $ret->createFormControl('reset', $theResetName);
      $reset->setValue($theResetButtonText);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form control and adds this form control to this fieldset.
   *
   * @param string $theType          The type of the form control.
   * @param string $theName          The name of the form control.
   * @param int    $theWrdId         The wrd_id of the legend of the form control.
   * @param bool   $theMandatoryFlag If set the form control is mandatory.
   *
   * @return \SetBased\Html\Form\Control\CheckBoxesControl|\SetBased\Html\Form\Control\ComplexControl|\SetBased\Html\Form\Control\RadiosControl|\SetBased\Html\Form\Control\SelectControl|\SetBased\Html\Form\Control\SimpleControl|\SetBased\Html\Form\Control\SpanControl
   */
  public function createFormControl($theType, $theName, $theWrdId = null, $theMandatoryFlag = false)
  {
    switch ($theType)
    {
      case 'text':
        $control = new TextControl($theName);
        $ret     = $this->addFormControl($control);
        $ret->setAttribute('size', '80');
        break;

      default:
        $ret = parent::createFormControl($theType, $theName);
    }

    if ($theWrdId)
    {
      if (is_int($theWrdId))
      {
        $ret->setAttribute('_abc_label', Babel::getWord($theWrdId));
      }
      else
      {
        $ret->setAttribute('_abc_label', $theWrdId);
      }
    }

    if ($theMandatoryFlag)
    {
      $ret->addValidator(new MandatoryValidator(0, 0));
      $ret->setAttribute('_set_mandatory', true);
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate($theParentName)
  {
    $submit_name = $this->getSubmitName($theParentName);

    $ret = $this->generateOpenTag();

    $ret .= '<div class="input_table">';
    $ret .= '<table>';

    if ($this->myHtmlTitle)
    {
      $ret .= '<thead>';
      $ret .= '<tr>';
      $ret .= '<th colspan="2">'.$this->myHtmlTitle.'</th>';
      $ret .= '</tr>';
      $ret .= '</thead>';
    }

    if ($this->myButtonFormControl)
    {
      $ret .= '<tfoot class="button">';
      $ret .= '<tr>';
      $ret .= '<td colspan="2">';
      $ret .= $this->myButtonFormControl->generate($submit_name);
      $ret .= '</td>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '<tbody>';
    foreach ($this->myControls as $control)
    {
      if ($control!=$this->myButtonFormControl)
      {
        $ret .= '<tr>';
        $ret .= '<th>';
        $ret .= Html::txt2html($control->GetAttribute('_abc_label'));
        if ($control->getAttribute('_set_mandatory')) $ret .= '<span class="mandatory">*</span>';
        $ret .= '</th>';

        $ret .= '<td>';
        $ret .= $control->generate($submit_name);
        $ret .= '</td>';

        $errmsg = $control->getErrorMessages(true);
        if ($errmsg)
        {
          $ret .= '<td class="error">';
          $first = true;
          foreach ($errmsg as $err)
          {
            if ($first) $first = false;
            else        $ret .= '<br/>';
            $ret .= Html::txt2html($err);
          }
          $ret .= '</td>';
        }

        $ret .= '</tr>';
      }
    }

    $ret .= '</tbody>';
    $ret .= '</table>';
    $ret .= '</div>';

    $ret .= $this->generateCloseTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of the form of this field set.
   *
   * @param string $theTitle
   */
  public function setTitle($theTitle)
  {
    $this->myHtmlTitle = Html::txt2Html($theTitle);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
