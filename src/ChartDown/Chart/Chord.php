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
 * Represents a chart chord.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Chord
{
    const EXTENSION_REGEX = '/(b2|2|6|7|M7|b9|9|#9|#11|b13|13|sus)/';

    protected $value;
    protected $text;
    protected $interval;
    protected $accidental;
    protected $bass;
    protected $notations;

    protected $extensions = array();
    protected $expressions = array();

    public function __construct($chord)
    {
        $this->parseChord($chord);
    }

    public function __toString()
    {
        return $this->text;
    }

    protected function parseChord($chord)
    {
        // remember original text
        $this->text = $chord;

        // Bass Note
        if (false !== $pos = strpos($chord, '/')) {
            $bass = substr($chord, $pos + 1);
            $chord = substr($chord, 0, $pos);

            $this->bass = new ChartDown_Chart_Note($bass);
        }

        // Chord Value
        if (!preg_match('/[A-G1-7]/', strtoupper($chord[0]))) {
            throw new InvalidArgumentException(sprintf("Cannot parse '%s' as a chord letter", $chord[0]));
        }

        $root = $chord[0];

        $i = 1;

        if (strlen($chord) > $i) {
            // Accidental
            if (preg_match('/[b#]/', $chord[$i])) {
                $this->accidental = $chord[$i] == 'b' ? ChartDown::FLAT : ChartDown::SHARP;
                $root .= $chord[$i];
                $i++;
            }
        }

        $this->root = new ChartDown_Chart_Note($root);

        if (strlen($chord) > $i) {
            // Minor / Diminished / Augmented
            if (preg_match('/[+m-]/', $chord[$i])) {
                $this->interval = $chord[$i] === '+' ? ChartDown::AUGMENTED : ChartDown::MINOR;
                $i++;
            } elseif (strpos(strtolower(substr($chord, $i)), 'dim') === 0) {
                $this->interval = ChartDown::DIMINISHED;
                $i+=3;
            } else {
                $this->interval = ChartDown::MAJOR;
            }

            $chord = substr($chord, $i);

            // Match Extensions (b7, #11, sus, etc)
            if (preg_match_all(self::EXTENSION_REGEX, $chord, $matches)) {
                $this->extensions = $matches[0];

                $chord = preg_replace(self::EXTENSION_REGEX, '', $chord);
            }

            // Add the rest to notations;
            $this->notations = $chord;
        }
    }

    public function getRoot()
    {
        return $this->root->getNote();
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    public function getBass()
    {
        return $this->bass ? $this->bass->getNote() : null;
    }

    public function getRootNote()
    {
        return $this->root;
    }

    public function getBassNote()
    {
        return $this->bass;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getNotations()
    {
        return $this->notations;
    }

    public function addExpression(ChartDown_Chart_Expression $expression)
    {
        $this->expressions[] = $expression;
    }

    public function addExpressions(array $expressions)
    {
        foreach ($expressions as $expression) {
            $this->addExpression($expression);
        }
    }

    public function getExpressions()
    {
        return $this->expressions;
    }
}
