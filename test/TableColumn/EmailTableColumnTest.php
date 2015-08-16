<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\TableColumn\EmailTableColumn;

//----------------------------------------------------------------------------------------------------------------------
class EmailTableColumnTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with a valid email address.
   */
  public function testValidAddress1()
  {
    $column = new EmailTableColumn( 'header', 'mail' );
    $row    = ['mail' => 'info@setbased.nl'];
    $ret    = $column->getHtmlCell( $row );

    $this->assertEquals( '<td><a href="mailto:info@setbased.nl">info@setbased.nl</a></td>', $ret );
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an empty email address.
   */
  public function testEmptyAddress()
  {
    $column = new EmailTableColumn( 'header', 'mail' );
    $row    = ['mail' => ''];
    $ret    = $column->getHtmlCell( $row );

    $this->assertEquals( '<td></td>', $ret );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
