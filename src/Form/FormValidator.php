<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Interface for defining classes that validate forms at form level.
 */
interface FormValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates a form at form level.
   *
   * @param Form $theForm The form to be validated.
   *
   * @return bool On Successful validation returns true, otherwise false.
   */
  public function validate($theForm);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------