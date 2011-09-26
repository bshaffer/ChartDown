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
    private $bottomText;
    private $topText;
    private $options;
    private $rhythm;

    private $expressions = array();

    public function __construct($options = array())
    {
        $this->options = $options;
    }

    public function getText($position = null)
    {
        if (is_null($position) || $position == 'top') {
            return $this->getTopText();
        }

        return $this->getBottomText();
    }
    
    public function setText($text, $position = null)
    {
        if (0 == count($this->getChords()) || $position == 'top') {
            $this->setTopText($text);
        } else {
            $this->setBottomText($text);
        }
    }
    
    public function hasText($position = null)
    {
        if (0 == count($this->getChords()) || $position == 'top') {
            return $this->hasTopText();
        } 
        
        return $this->hasBottomText();
    }
    
    public function renderText($position = null)
    {
        return $this->getText($position)->getText();
    }

    public function getTopText()
    {
        return $this->topText;
    }
    
    public function setTopText($text)
    {
        if (is_string($text)) {
            $text = new ChartDown_Chart_Text($text);
        }

        if (!empty($this->topText)) { 
            $this->topText->addText($text->getRawText());
        } else { 
            $this->topText = $text;
        }
    }
    
    public function hasTopText()
    {
        return !is_null($this->topText);
    }

    public function renderTopText()
    {
        return $this->topText->getText();
    }

    public function getBottomText()
    {
        return $this->bottomText;
    }
    
    public function setBottomText($text)
    {
        if (is_string($text)) {
            $text = new ChartDown_Chart_Text($text);
        }

        if (!empty($this->bottomText)) { 
            $this->bottomText->addText($text->getRawText());
        } else { 
            $this->bottomText = $text;
        }
    }

    public function hasBottomText()
    {
        return !is_null($this->bottomText);
    }

    public function renderBottomText()
    {
        return $this->bottomText->getText();
    }

    public function addChordGroup()
    {
        $group = new ChartDown_Chart_ChordGroup();

        $this->addChord($group);

        return new ChartDown_FluidObjectTraverser($group, $this);
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
            $expression = new ChartDown_Chart_Expression($expression);
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

    public function setRhythm(ChartDown_Chart_Rhythm $rhythm)
    {
        $this->rhythm = $rhythm;
    }
}