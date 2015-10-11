<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A pseudo form control for generating (pseudo) form controls in a table format.
 */
class LouverControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The data on which the table row form controls must be created.
   *
   * @var array[]
   */
  protected $myData;

  /**
   * Form control for the footer of the table.
   *
   * @var control
   */
  protected $myFooterControl;

  /**
   * Object for creating table row form controls.
   *
   * @var SlatControlFactory
   */
  protected $myRowFactory;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of displaying the form controls of this complex form control in a table.
   *
   * @param string $theParentName
   *
   * @return string
   */
  public function generate($theParentName)
  {
    $submit_name = $this->getSubmitName($theParentName);

    $ret = $this->myPrefix;

    $ret .= Html::generateTag('div', $this->myAttributes);
    $ret .= '<table>';

    // Generate HTML code for the column classes.
    $ret .= '<colgroup>';
    $ret .= $this->myRowFactory->getColumnGroup();
    $ret .= '</colgroup>';

    $ret .= '<thead>';
    $ret .= $this->getHtmlHeader();
    $ret .= '</thead>';

    if ($this->myFooterControl)
    {
      $ret .= '<tfoot>';
      $ret .= '<tr>';
      $ret .= '<td colspan="'.$this->myRowFactory->getNumberOfColumns().'">';
      $ret .= $this->myFooterControl->generate($submit_name);
      $ret .= '</td>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '<tbody>';
    $ret .= $this->getHtmlBody($submit_name);
    $ret .= '</tbody>';

    $ret .= '</table>';
    $ret .= '</div>';

    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Populates this table form control with table row form controls (based on the data set with setData).
   */
  public function populate()
  {
    foreach ($this->myData as $data)
    {
      $this->myRowFactory->createRow($this, $data);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the data for which this table form control must be generated.
   *
   * @param array[] $theData
   */
  public function setData($theData)
  {
    $this->myData = $theData;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the footer form control of this table form control.
   *
   * @param Control $theControl
   */
  public function setFooterControl($theControl)
  {
    $this->myFooterControl = $this->addFormControl($theControl);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the row factory for this table form control.
   *
   * @param SlatControlFactory $theRowFactory
   */
  public function setRowFactory($theRowFactory)
  {
    $this->myRowFactory = $theRowFactory;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the tbody element of this table form control.
   *
   * @param string $theSubmitParentName The submit name of the parent form control of this table form control.
   *
   * @return string
   */
  protected function getHtmlBody($theSubmitParentName)
  {
    $ret = '';
    $i   = 0;
    foreach ($this->myControls as $control)
    {
      if ($control!==$this->myFooterControl)
      {
        // Add class for zebra theme.
        $control->setAttrClass(($i % 2==0) ? 'even' : 'odd');

        // Generate the table row.
        $ret .= $control->generate($theSubmitParentName);

        $i++;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the thead element (e.g. column headers and filters) of this table form control.
   *
   * @return string
   */
  protected function getHtmlHeader()
  {
    return $this->myRowFactory->getHtmlHeader();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
