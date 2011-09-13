<?php

class ChartDown_Tests_Chart_ChordTest extends PHPUnit_Framework_TestCase
{
  public function testParseBasicChord()
  {
    $chord1 = new ChartDown_Chart_Chord('G');
    $chord2 = new ChartDown_Chart_Chord('Bb');
    $chord3 = new ChartDown_Chart_Chord('F#');

    $this->assertEquals($chord1->getRoot(), ChartDown::G);

    $this->assertEquals($chord2->getRoot(), ChartDown::B_FLAT);

    $this->assertEquals($chord3->getRoot(), ChartDown::F_SHARP);
  }

  public function testParseChordWithBass()
  {
    $chord1 = new ChartDown_Chart_Chord('G/B');

    $this->assertEquals($chord1->getRoot(), ChartDown::G);
    $this->assertEquals($chord1->getBass(), ChartDown::B);
  }

  public function testParseMinorChord()
  {
    $chord1 = new ChartDown_Chart_Chord('Gm');

    $this->assertEquals($chord1->getRoot(), ChartDown::G);
    $this->assertEquals($chord1->getInterval(), ChartDown::MINOR);
  }

  public function testParseDiminishedChord()
  {
    $chord1 = new ChartDown_Chart_Chord('F#dim');

    $this->assertEquals($chord1->getRoot(), ChartDown::F_SHARP);
    $this->assertEquals($chord1->getInterval(), ChartDown::DIMINISHED);
  }

  public function testParseAugmentedChord()
  {
    $chord1 = new ChartDown_Chart_Chord('A#+');

    $this->assertEquals($chord1->getRoot(), ChartDown::A_SHARP);
    $this->assertEquals($chord1->getInterval(), ChartDown::AUGMENTED);
  }

  public function testParseChordWithExtension()
  {
    $chord1 = new ChartDown_Chart_Chord('C7');

    $this->assertEquals($chord1->getRoot(), ChartDown::C);
    $this->assertEquals($chord1->getExtensions(), array('7'));
  }

  public function testParseChordWithMultipleExtensions()
  {
    $chord1 = new ChartDown_Chart_Chord('D7b13');

    $this->assertEquals($chord1->getRoot(), ChartDown::D);
    $this->assertEquals($chord1->getExtensions(), array('7', 'b13'));
  }

  public function testParseComplexChord()
  {
    $chord1 = new ChartDown_Chart_Chord('Dbm7#11/A#');

    $this->assertEquals($chord1->getRoot(), ChartDown::D_FLAT);
    $this->assertEquals($chord1->getInterval(), ChartDown::MINOR);
    $this->assertEquals($chord1->getExtensions(), array('7', '#11'));
    $this->assertEquals($chord1->getBass(), ChartDown::A_SHARP);
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidNote()
  {
    $note = new ChartDown_Chart_Chord('H#');
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidExtension()
  {
    $note = new ChartDown_Chart_Chord('H#14');
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidBass()
  {
    $note = new ChartDown_Chart_Chord('Bm/H');
  }
}
