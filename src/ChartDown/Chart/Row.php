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
 * Represents a chart.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Row implements IteratorAggregate
{
    protected $bars;

    public function __construct($bars = array())
    {
        $this->bars = $bars;
    }

    public function getBars()
    {
        return $this->bars;
    }
    
    public function hasChords()
    {
        foreach ($this->bars as $bar) {
            if ($bar->getChords()) {
                return true;
            }
        }
        
        return false;
    }
    
    public function hasText($position = null)
    {
        if (is_null($position) || $position == 'top') {
            return $this->hasTopText();
        }
        
        return $this->hasBottomText();
    }
    
    public function hasTopText()
    {
      foreach ($this->bars as $bar) {
        if ($bar->hasTopText()) {
          return true;
        }
      }
      
      return false;
    }

    public function hasBottomText()
    {
      foreach ($this->bars as $bar) {
        if ($bar->hasBottomText()) {
          return true;
        }
      }
      
      return false;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->bars);
    }
}
