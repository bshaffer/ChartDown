<?php

use ChartDown\Chart\Note;

class ChartDown_Tests_Chart_NoteTest extends PHPUnit_Framework_TestCase
{
  public function testParseNote()
  {
    $note1 = new Note('G');
    $note2 = new Note('Bb');
    $note3 = new Note('F#');
    
    $this->assertEquals($note1->getLetter(), ChartDown::G);
    $this->assertEquals($note1->getAccidental(), null);

    $this->assertEquals($note2->getLetter(), ChartDown::B);
    $this->assertEquals($note2->getAccidental(), ChartDown::FLAT);

    $this->assertEquals($note3->getLetter(), ChartDown::F);
    $this->assertEquals($note3->getAccidental(), ChartDown::SHARP);
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidNote()
  {
    $note = new Note('H#');
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidAccidental()
  {
    $note = new Note('Bm');
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidLength()
  {
    $note = new Note('Bbm');
  }
}
