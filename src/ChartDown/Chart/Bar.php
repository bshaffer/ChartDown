<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2011 Brent Shaffer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a bar in a chart.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Bar
{
  protected $options;
  protected $lyric;
  protected $expression;
  protected $rhythm;
  protected $label;
  protected $chords;

  public function __construct($options = array())
  {
    $this->options = $options;
    $this->chords = array();
  }
  
  public function getLabel()
  {
    return $this->label;
  }
  
  public function setLabel(ChartDown_Chart_Label $label)
  {
    $this->label = $label;
  }
  
  public function getLyric()
  {
    return $this->lyric;
  }
  
  public function addLyric(ChartDown_Chart_Lyric $lyric)
  {
    $this->lyric = $lyric;
  }
    
  public function addChord(ChartDown_Chart_Chord $chord)
  {
    $this->chords[] = $chord;
  }
  
  public function getChords()
  {
    return $this->chords;
  }
  
  public function getExpression()
  {
    return $this->expression;
  }
  
  public function setExpression(ChartDown_Chart_Expression $expression)
  {
    $this->expression = $expression;
  }
  
  public function getRhythm()
  {
    return $this->rhythm;
  }
  
  public function setRhythm(ChartDown_Chart_Rhythm $rhythm)
  {
    $this->rhythm = $rhythm;
  }
}