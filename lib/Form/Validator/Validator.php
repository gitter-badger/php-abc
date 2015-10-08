<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

/**
 * IInterface for defining validators on form controls.
 */
interface Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control meets the conditions of this validator. Returns false otherwise.
   *
   * @param Control $theFormControl The form control.
   *
   * @return bool
   */
  public function validate($theFormControl);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
