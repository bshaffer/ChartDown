<?php

namespace ChartDown\Chart;
use ChartDown\Chart\Rhythm\RelativeMeterInterface;

class ChordGroup implements IteratorAggregate, RelativeMeterInterface
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
            $chord = new Chord($chord);
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
            $expression = new Expression($expression);
        }
        
        if ($expression->getType() instanceof RelativeMeterInterface) {
            $this->chords[] = $expression;
        } else {
            $this->expressions[] = $expression;
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->chords);
    }
    
    public function addRhythm($rhythm)
    {
        if (is_string($rhythm)) {
            $rhythm = new Rhythm($rhythm);
        }
        
        $this->chords[] = $rhythm;
    }
    
    public function getRelativeMeter()
    {
        return 1;
    }
}