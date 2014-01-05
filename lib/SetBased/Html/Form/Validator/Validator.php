<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Validator;

use SetBased\Html\Form\Control\Control;

/**
 * Interface Validator
 * Interface for defining classes that validate form control elements derived from FormControl
 *
 * @package SetBased\Html\Form
 */
interface Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param Control $theFormControl
   *
   * @return bool
   */
  public function validate( $theFormControl );

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
