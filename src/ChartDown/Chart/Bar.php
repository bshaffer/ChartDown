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
    
    public function getTopText()
    {
        return $this->topText;
    }

    public function hasTopText()
    {
        return !empty($this->topText);
    }

    public function getBottomText()
    {
        return $this->bottomText;
    }

    public function hasBottomText()
    {
        return !empty($this->bottomText);
    }

    public function setText($text)
    {        
        if (0 == count($this->getChords())) {
            $this->setTopText($text);
        } else {
            $this->setBottomText($text);
        }
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