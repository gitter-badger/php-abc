<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class SubmitControl
 * Class for form controls of type input:submit.
 *
 * @package SetBased\Html\Form\Control
 */
class SubmitControl extends PushMeControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myButtonType = 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
