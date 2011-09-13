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
class ChartDown_Chart_Bar extends ChartDown_Chart_ChordGroup
{
    protected $lyric;
    protected $label;
    protected $options;
    protected $rhythm;

    protected $expressions = array();

    public function __construct($options = array())
    {
        $this->options = $options;
        $this->chords = array();
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        if (is_string($label)) {
            $label = new ChartDown_Chart_Label($label);
        }

        $this->label = $label;
    }

    public function getLyric()
    {
        return $this->lyric;
    }

    public function addLyric($lyric)
    {
        if (is_string($lyric)) {
            $lyric = new ChartDown_Chart_Lyric($lyric);
        }

        $this->lyric = $lyric;
    }

    public function addChordGroup()
    {
        $group = new ChartDown_Chart_ChordGroup();

        $this->chords[] = $group;

        return new ChartDown_FluidObjectTraverser($group, $this);
    }

    public function getExpressions()
    {
        return $this->expressions;
    }

    public function getExpressionByType($type)
    {
        foreach ($this->expressions as $expression) {
            if ($expression->getType() == $type) {
                return $expression;
            }
        }

        return false;
    }

    public function addExpression($expression)
    {
        if (is_string($expression)) {
            $expression = new ChartDown_Chart_Expression($expression);
        }

        if ($expression->isChordExpression()) {
            parent::addExpression($expression);
        }
        else {
            $this->expressions[] = $expression;
        }
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