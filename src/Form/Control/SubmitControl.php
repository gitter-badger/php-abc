<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for form controls of type input:submit.
 */
class SubmitControl extends PushMeControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct($theName)
  {
    parent::__construct($theName);

    $this->myButtonType = 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------