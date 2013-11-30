<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Html\Form\Control;

use SetBased\Html\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Html\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class TextAreaControl
 *
 * @package SetBased\Html\Form\Control
 */
class TextAreaControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theName
   */
  public function __construct( $theName )
  {
    parent::__construct( $theName );

    // By default whitespace is trimmed from textarea form controls.
    $this->myCleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $theParentName
   *
   * @return string
   */
  public function generate( $theParentName )
  {
    $this->myAttributes['name'] = $this->getSubmitName( $theParentName );

    $html = $this->myPrefix;

    $html .= '<textarea';
    foreach ($this->myAttributes as $name => $value)
    {
      $html .= Html::generateAttribute( $name, $value );
    }
    $html .= '>';

    $html .= Html::txt2Html( $this->myValue );

    $html .= "</textarea>";
    $html .= $this->myPostfix;

    return $html;
  }

   //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $theSubmittedValue
   * @param array $theWhiteListValue
   * @param array $theChangedInputs
   */
  protected function loadSubmittedValuesBase( &$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs )
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode( $this->myName ) : $this->myName;

    // Get the submitted value and cleaned (if required).
    if ($this->myCleaner)
    {
      $new_value = $this->myCleaner->clean( $theSubmittedValue[$submit_name] );
    }
    else
    {
      $new_value = $theSubmittedValue[$submit_name];
    }

    // Normalize old (original) value and new (submitted) value.
    $old_value = (string)$this->myValue;
    $new_value = (string)$new_value;

    if ($old_value!==$new_value)
    {
      $theChangedInputs[$this->myName] = $this;
      $this->myValue                   = $new_value;
    }

    $theWhiteListValue[$this->myName] = $new_value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
