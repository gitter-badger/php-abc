<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\FormValidator;

use SetBased\Abc\Form\FormValidator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Form validator for the form for inserting or updating a module.
 */
class SystemModuleInsertFormValidator implements FormValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function validate($theForm)
  {
    $ret = true;

    $values = $theForm->getValues();

    // Only and only one of mdl_name or wrd_id must be set.
    if (!isset($values['mdl_name']) && !$values['wrd_id'])
    {
      $control = $theForm->getFormControlByName('wrd_id');
      $control->setErrorMessage("Verplicht veld");

      $control = $theForm->getFormControlByName('mdl_name');
      $control->setErrorMessage("Verplicht veld");

      $ret = false;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
