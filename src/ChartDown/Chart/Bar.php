<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2011 Brent Shaffer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChartDown\Chart;

use ChartDown\Chart\Rhythm\Rhythm;

/**
 * Represents a bar in a chart.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class Bar extends ChordGroup
{
    private $text;
    private $options;
    private $rhythm;

    private $expressions = array();

    public function __construct($options = array())
    {
        $this->options = $options;
    }

    public function getText()
    {
        return $this->text;
    }
    
    public function setText($text)
    {
        if (is_string($text)) {
            $text = new Text($text);
        }

        if (!empty($this->text)) { 
            $this->text->addText($text->getRawText());
        } else { 
            $this->text = $text;
        }
    }
    
    public function hasText()
    {
        return !is_null($this->text);
    }

    public function renderText()
    {
        return $this->text->getText();
    }
    
    public function hasChords()
    {
        return count($this->getChords()) > 0;
    }

    public function addChordGroup()
    {
        $group = new ChordGroup();

        $this->addChord($group);

        return new FluidObjectTraverser($group, $this);
    }

    public function getExpressions()
    {
        return $this->expressions;
    }

    public function hasRepeatEnding()
    {
        foreach ($this->expressions as $expression) {
            if ($expression->getType()->getName() == 'repeat ending') {
                return $expression;
            }
        }

        return false;
    }

    public function addExpression($expression)
    {
        if (is_string($expression)) {
            $expression = new Expression($expression);
        }

        if ($expression->getPosition() == 'bar' || $expression->getPosition() == 'bar-top') {
            $this->expressions[] = $expression;
        } else {
            parent::addExpression($expression);
        }
    }

    public function getRhythm()
    {
        return $this->rhythm;
    }

    public function setRhythm(Rhythm $rhythm)
    {
        $this->rhythm = $rhythm;
    }
}