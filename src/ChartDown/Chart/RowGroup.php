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
class RowGroup implements IteratorAggregate
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
    
    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->rows);
    }
}