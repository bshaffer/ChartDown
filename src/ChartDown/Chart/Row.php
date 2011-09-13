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
    public function __construct($bars)
    {
        $this->bars = $bars;
    }

    public function getBars()
    {
        return $this->bars;
    }
    
    public function hasLabel()
    {
      foreach ($this->bars as $bar) {
        if (trim($bar->getLabel()) != '') {
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
