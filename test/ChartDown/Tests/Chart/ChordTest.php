<?php

use ChartDown\Chart\Chord;

class ChartDown_Tests_Chart_ChordTest extends PHPUnit_Framework_TestCase
{
  public function testParseBasicChord()
  {
    $chord1 = new Chord('G');
    $chord2 = new Chord('Bb');
    $chord3 = new Chord('F#');

    $this->assertEquals($chord1->getRoot(), ChartDown::G);

    $this->assertEquals($chord2->getRoot(), ChartDown::B_FLAT);

    $this->assertEquals($chord3->getRoot(), ChartDown::F_SHARP);
  }

  public function testParseChordWithBass()
  {
    $chord1 = new Chord('G/B');

    $this->assertEquals($chord1->getRoot(), ChartDown::G);
    $this->assertEquals($chord1->getBass(), ChartDown::B);
  }

  public function testParseMinorChord()
  {
    $chord1 = new Chord('Gm');

    $this->assertEquals($chord1->getRoot(), ChartDown::G);
    $this->assertEquals($chord1->getInterval(), ChartDown::MINOR);
  }

  public function testParseDiminishedChord()
  {
    $chord1 = new Chord('F#dim');

    $this->assertEquals($chord1->getRoot(), ChartDown::F_SHARP);
    $this->assertEquals($chord1->getInterval(), ChartDown::DIMINISHED);
  }

  public function testParseAugmentedChord()
  {
    $chord1 = new Chord('A#+');

    $this->assertEquals($chord1->getRoot(), ChartDown::A_SHARP);
    $this->assertEquals($chord1->getInterval(), ChartDown::AUGMENTED);
  }

  public function testParseChordWithExtension()
  {
    $chord1 = new Chord('C7');

    $this->assertEquals($chord1->getRoot(), ChartDown::C);
    $this->assertEquals($chord1->getExtensions(), array('7'));
  }

  public function testParseChordWithMultipleExtensions()
  {
    $chord1 = new Chord('D7b13');

    $this->assertEquals($chord1->getRoot(), ChartDown::D);
    $this->assertEquals($chord1->getExtensions(), array('7', 'b13'));
  }

  public function testParseComplexChord()
  {
    $chord1 = new Chord('Dbm7#11/A#');

    $this->assertEquals($chord1->getRoot(), ChartDown::D_FLAT);
    $this->assertEquals($chord1->getInterval(), ChartDown::MINOR);
    $this->assertEquals($chord1->getExtensions(), array('7', '#11'));
    $this->assertEquals($chord1->getBass(), ChartDown::A_SHARP);
  }

  public function testParseInvalidNote()
  {
    $note = new Chord('H#');
    $this->assertEquals($note->getText(), 'H#');
  }

  public function testParseInvalidExtension()
  {
    $note = new Chord('H#14');
    $this->assertEquals($note->getText(), 'H#14');
  }

  public function testParseInvalidBass()
  {
    $note = new Chord('Bm/H');
    $this->assertEquals($note->getText(), 'Bm/H');
  }
}
