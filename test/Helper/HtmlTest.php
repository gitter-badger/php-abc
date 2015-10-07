<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
class HtmlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateTag.
   */
  public function testGenerateTag1()
  {
    $tag = Html::generateTag('a', ['href' => 'https://www.setbased.nl'], 'SetBased');
    $this->assertEquals('<a href="https://www.setbased.nl">SetBased</a>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes with leading underscores are ignored.
   */
  public function testGenerateTag2()
  {
    $tag = Html::generateTag('a', ['href' => 'https://www.setbased.nl', '_ignore' => 'ignored'], 'SetBased');
    $this->assertEquals('<a href="https://www.setbased.nl">SetBased</a>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateVoidTag.
   */
  public function testGenerateVoidTag1()
  {
    $tag = Html::generateVoidTag('img', ['src' => '/images/logo.png', 'alt' => 'logo']);
    $this->assertEquals('<img src="/images/logo.png" alt="logo"/>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes with leading underscores are ignored.
   */
  public function testGenerateVoidTag2()
  {
    $tag = Html::generateVoidTag('img', ['src' => '/images/logo.png', 'alt' => 'logo', '_ignore' => 'ignored']);
    $this->assertEquals('<img src="/images/logo.png" alt="logo"/>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


