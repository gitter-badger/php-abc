<?php

namespace SetBased\Abc\Core\Form\Validator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validator for validating that a form control has been filled out.
 */
class MandatoryValidator extends \SetBased\Html\Form\Validator\MandatoryValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The text ID (txt_id) of the error message.
   *
   * @var int
   */
  private $myTxtId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theTxtId The text ID of the error message.
   */
  public function __construct($theTxtId)
  {
    $this->myTxtId = $theTxtId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function validate($theFormControl)
  {
    $valid = parent::validate($theFormControl);

    if (!$valid)
    {
      // @todo Improve validator error messages.
      $errmsg = 'Verplicht veld.';
      $theFormControl->setErrorMessage($errmsg);
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
