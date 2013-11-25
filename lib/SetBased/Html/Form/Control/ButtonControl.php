<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

//----------------------------------------------------------------------------------------------------------------------
  /**
   * Class for form controls of type input:button.
   * Class ButtonControl
   *
   * @package SetBased\Html\Form
   */
/**
 * Class ButtonControl
 *
 * @package SetBased\Html\Form\Control
 */
class ButtonControl extends PushMeControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    $this->myButtonType = 'button';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
