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
class ChartDown_Chart implements IteratorAggregate
{
    protected $title;
    protected $Author;
    protected $key;
    protected $raw;
    protected $timeSignature;
    protected $tempo;
    protected $bars;

    public function __construct($options = array())
    {
        $this->options = $options;
        $this->bars   = array();

        $this->setup();
    }

    public function setup()
    {
    }

    public function getTimeSignature()
    {
        if ($this->timeSignature === null) {
          $this->timeSignature = new ChartDown_Chart_TimeSignature(4, 4);
        }

        return $this->timeSignature;
    }

    public function setTimeSignature(ChartDown_Chart_TimeSignature $timeSignature)
    {
        $this->timeSignature = $timeSignature;
    }

    public function getKey()
    {
        if ($this->key === null) {
          $this->key = new ChartDown_Chart_Key();
        }

        return $this->key;
    }

    public function setKey(ChartDown_Chart_Key $key)
    {
        $this->key = $key;
    }

    public function getTempo()
    {
        if ($this->tempo === null) {
          $this->tempo = new ChartDown_Chart_Tempo();
        }

        return $this->tempo;
    }

    public function setTempo(ChartDown_Chart_Tempo $tempo)
    {
        $this->tempo = $tempo;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getAuthor()
    {
        return $this->Author;
    }

    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    public function getRaw()
    {
        return $this->raw;
    }

    public function setAuthor($Author)
    {
        $this->Author = $Author;
    }

    public function addBar(ChartDown_Chart_Bar $bar = null)
    {
        if (is_null($bar)) {
          $bar = new ChartDown_Chart_Bar();
        }
        $this->bars[] = $bar;
        
        return new ChartDown_FluidObjectTraverser($bar, $this);
    }

    public function setBars($bars)
    {
        $this->bars = $bars;
    }

    public function getBars()
    {
        return $this->bars;
    }
    
    public function getRows()
    {
        $bars = array();
        $rows = array();
        foreach ($this->bars as $bar) {
            if (is_null($bar)) {
                $rows[] = new ChartDown_Chart_Row($bars);
                $bars = array();
            } else {
                $bars[] = $bar;
            }
        }
        
        if (count($bars) > 0) {
            $rows[] = new ChartDown_Chart_Row($bars);
        }
        
        return $rows;
    }

    public function endRow()
    {
        $this->bars[] = null;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->bars);
    }
}
