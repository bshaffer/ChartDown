<?php

class ChartDown_Chart_ChordGroup implements IteratorAggregate, ChartDown_Chart_Rhythm_RelativeMeterInterface
{
    private $chords = array();
    private $expressions = array();

    public function getChords()
    {
        return $this->chords;
    }

    public function addChord($chord)
    {
        if (is_string($chord)) {
            $chord = new ChartDown_Chart_Chord($chord);
        }

        if ($this->expressions) {
            $chord->addExpressions($this->expressions);
            $this->expressions = array();
        }

        $this->chords[] = $chord;
    }

    public function addExpression($expression)
    {
        if (is_string($expression)) {
            $expression = new ChartDown_Chart_Expression($expression);
        }
        
        $this->expressions[] = $expression;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->chords);
    }
    
    public function addRhythm($rhythm)
    {
        if (is_string($rhythm)) {
            $rhythm = new ChartDown_Chart_Rhythm($rhythm);
        }
        
        $this->chords[] = $rhythm;
    }
    
    public function getRelativeMeter()
    {
        return 1;
    }
}