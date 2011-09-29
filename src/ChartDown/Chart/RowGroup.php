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
class ChartDown_Chart_RowGroup implements IteratorAggregate
{
    protected $rows;

    public function __construct($rows = array())
    {
        $this->rows = $rows;
    }

    public function getRows()
    {
        return $this->rows;
    }
    
    public function addRow(ChartDown_Chart_Row $row)
    {
        $this->rows[] = $row;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->rows);
    }
}