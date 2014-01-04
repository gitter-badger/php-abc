<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Html\Form\Control\LouverControl;
use SetBased\Html\Form\Control\SlatControl;
use SetBased\Html\Form\Control\SlatControlFactory;
use SetBased\Html\Form\SlatJoint\TextSlatJoint;

//----------------------------------------------------------------------------------------------------------------------
require __DIR__.'/../vendor/autoload.php';

//----------------------------------------------------------------------------------------------------------------------
class DeadPoetSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  public function __construct()
  {
    $this->mySlatJoints['birthday']      = new TextSlatJoint('Birthday');
    $this->mySlatJoints['date_of_death'] = new TextSlatJoint('Date of Death');
    $this->mySlatJoints['sir_name']      = new TextSlatJoint('Sir Name');
    $this->mySlatJoints['given_name']    = new TextSlatJoint('Given Name');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param LouverControl $theLouverControl
   * @param array        $theData
   */
  public function createRow( $theLouverControl, $theData )
  {
    $row = new SlatControl($theData['id']);

    $factory = $this->mySlatJoints['birthday'];
    $control = $factory->createCell( 'birthday' );
    $control->setAttribute( 'maxlength', 10 );
    $control->setValue( $theData['birthday'] );
    $row->addFormControl( $control );

    $factory = $this->mySlatJoints['date_of_death'];
    $control = $factory->createCell( 'date_of_death' );
    $control->setAttribute( 'maxlength', 10 );
    $control->setValue( $theData['date_of_death'] );
    $row->addFormControl( $control );

    $factory = $this->mySlatJoints['sir_name'];
    $control = $factory->createCell( 'sir_name' );
    $control->setAttribute( 'maxlength', 40 );
    $control->setValue( $theData['sir_name'] );
    $row->addFormControl( $control );

    $factory = $this->mySlatJoints['given_name'];
    $control = $factory->createCell( 'given_name' );
    $control->setAttribute( 'maxlength', 25 );
    $control->setValue( $theData['given_name'] );
    $row->addFormControl( $control );

    $theLouverControl->addFormControl( $row );
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
function leader()
{
  echo "<?xml version='1.0' encoding='UTF-8'?>\n";
  echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
  echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' dir='ltr'>\n";
  echo "<head>\n";
  echo "<meta content='text/html;charset=utf-8' http-equiv='Content-Type'/>\n";
  echo "<title>Sample Table Form</title>\n";
  echo "<script type='text/javascript' src='js/jquery/jquery.min.js'></script>\n";
  echo "<script type='text/javascript' src='js/overview_table.js'></script>\n";
  echo "<script type='text/javascript' src='js/sample11.js'></script>\n";
  echo "</head>\n";
  echo "<body>\n";
}

//----------------------------------------------------------------------------------------------------------------------
function trailer()
{
  echo "</body>\n";
  echo "</html>\n";
}

//----------------------------------------------------------------------------------------------------------------------
function createForm()
{
  $poets[] = array('id'            => 1,
                   'birthday'      => '1909-12-02',
                   'date_of_death' => '1993-09-19',
                   'given_name'    => 'Helen',
                   'sir_name'      => 'Adam',
                   'url'           => 'http://en.wikipedia.org/wiki/Helen_Adam');
  $poets[] = array('id'            => 2,
                   'birthday'      => '1889-06-23',
                   'date_of_death' => '1966-03-05',
                   'given_name'    => 'Anna',
                   'sir_name'      => 'Andreevna',
                   'url'           => 'http://en.wikipedia.org/wiki/Anna_Akhmatova');
  $poets[] = array('id'            => 3,
                   'birthday'      => '1914-02-04',
                   'date_of_death' => '1980-02-21',
                   'given_name'    => 'Alfred',
                   'sir_name'      => 'Andersch',
                   'url'           => 'http://en.wikipedia.org/wiki/Alfred_Andersch');
  $poets[] = array('id'            => 4,
                   'birthday'      => '1835-07-27',
                   'date_of_death' => '1907-02-16',
                   'given_name'    => 'Giosuè',
                   'sir_name'      => 'Carducci',
                   'url'           => 'http://en.wikipedia.org/wiki/Giosu%C3%A8_Carducci');


  $form = new \SetBased\Html\Form();

  $fieldset = $form->createFieldSet();
  $legend   = $fieldset->createLegend();
  $legend->setLegendText( 'Dead poets' );

  $factory = new DeadPoetSlatControlFactory();
  $factory->enableFilter();
  $control = new LouverControl('dead_poets');
  $control->setRowFactory( $factory );
  $control->setData( $poets );
  $control->populate();
  $fieldset->addFormControl( $control );

  return $form;
}

//----------------------------------------------------------------------------------------------------------------------
function demo()
{
  $form = createForm();

  if ($form->isSubmitted( 'submit' ))
  {
    $form->loadSubmittedValues();
    $valid = $form->validate();
    if (!$valid && false)
    {
      echo $form->generate();
    }
    else
    {
      echo "Html:";
      echo "<pre>";
      echo htmlentities( $form->generate() );
      echo "</pre>";

      echo "Post:";
      echo "<pre>";
      print_r( $_POST );
      echo "</pre>";

      echo "Values:";
      echo "<pre>";
      print_r( $form->getValues() );
      echo "</pre>";

      echo "Changed:";
      echo "<pre>";
      print_r( $form->getChangedControls() );
      echo "</pre>";

      echo "Invalid:";
      echo "<pre>";
      print_r( $form->getInvalidControls() );
      echo "</pre>";

      echo $form->generate();
    }
  }
  else
  {
    echo $form->generate();
  }
}

//----------------------------------------------------------------------------------------------------------------------
leader();
demo();
trailer();
