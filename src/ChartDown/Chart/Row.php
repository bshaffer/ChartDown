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

/**
 * Represents a chart.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class Row implements \IteratorAggregate
{
    private $bars;

    public function __construct($bars = array())
    {
        $this->bars = $bars;
    }

    public function getBars()
    {
        return $this->bars;
    }

    public function addBar(Bar $bar = null)
    {
        if (is_null($bar)) {
          $bar = new Bar();
        }
        $this->bars[] = $bar;
        
        return new FluidObjectTraverser($bar, $this);
    }
    
    public function hasBars()
    {
        return count($this->bars) > 0;
    }
    
    public function hasChords()
    {
        foreach ($this->bars as $bar) {
            if ($bar->hasChords()) {
                return true;
            }
        }
        return false;
    }
    
    public function hasText()
    {
        foreach ($this->bars as $bar) {
            if ($bar->hasText()) {
                return true;
            }
        }
        return false;
    }
    
    public function getIterator()
    {
        return new \ArrayIterator($this->bars);
    }
    
    public function isChordRow()
    {
        return $this->type == self::TYPE_CHORD;
    }
    
    public function isTextRow()
    {
        return $this->type == self::TYPE_TEXT;
    }
    
    public function isRowBreak()
    {
        return $this->type == self::TYPE_BREAK;
    }
}
