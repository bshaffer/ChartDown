<?php

use ChartDown\Chart\Transposer;
use ChartDown\Chart\Note;
use ChartDown\Chart\Chord;

class ChartDown_Tests_Chart_TransposerTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->transposer = new Transposer();
  }

  public function testTransposeNote()
  {
    $note = new Note('Bb');

    $this->transposer->transposeNote($note, 2);
    
    $this->assertEquals('C', $note->getLetter());
    $this->assertEquals(null, $note->getAccidental());
  }

  public function testTransposeNoteRollover()
  {
    $note = new Note('G');

    $this->transposer->transposeNote($note, 3);
    
    $this->assertEquals(ChartDown::B, $note->getLetter());
    $this->assertEquals(ChartDown::FLAT, $note->getAccidental());
  }

  public function testTransposeBasicChord()
  {
    $chord = new Chord('Bbm');

    $this->transposer->transposeChord($chord, 2);
    
    $this->assertEquals('C', $chord->getRoot());
    $this->assertEquals(ChartDown::MINOR, $chord->getInterval());
  }

  public function testTransposeChordWithBass()
  {
    $chord = new Chord('Cm/Eb');

    $this->transposer->transposeChord($chord, -2);
    
    $this->assertEquals(ChartDown::B_FLAT, $chord->getRoot());
    $this->assertEquals(ChartDown::C_SHARP, $chord->getBass());
    /*
        TODO -
            This should be "Db", because Eb is third in
            in the C minor scale, and Db is third in 
            the Bb minor scale!
    */
    $this->assertEquals('Bbm/C#', $chord->getText());
  }
}
