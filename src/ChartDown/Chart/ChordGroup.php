<?php

class ChartDown_Chart_ChordGroup implements IteratorAggregate
{
    protected $chords = array();
    protected $preExpressions = array();

    public function getChords()
    {
        return $this->chords;
    }

    public function addChord($chord)
    {
        if (is_string($chord)) {
            $chord = new ChartDown_Chart_Chord($chord);
        }

        if ($this->preExpressions) {
            $chord->addExpressions($this->preExpressions);
            $this->preExpressions = array();
        }

        $this->chords[] = $chord;
    }

    public function addExpression($expression)
    {
        if (is_string($expression)) {
            $expression = new ChartDown_Chart_Expression($expression);
        }

        if (!$expression->isPreChordExpression() && 0 < $len = count($this->chords)) {
            $this->chords[$len-1]->addExpression($expression);
        }
        else {
            $this->preExpressions[] = $expression;
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->chords);
    }
}