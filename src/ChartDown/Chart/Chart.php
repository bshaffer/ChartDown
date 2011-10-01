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
class Chart implements \IteratorAggregate
{
    protected $title;
    protected $artist;
    protected $key;
    protected $raw;
    protected $timeSignature;
    protected $tempo;
    protected $rows;

    public function __construct($options = array())
    {
        $this->options = $options;
        $this->rows    = array();

        $this->setup();
    }

    public function setup()
    {
    }

    public function getTimeSignature()
    {
        if ($this->timeSignature === null) {
          $this->timeSignature = new TimeSignature(4, 4);
        }

        return $this->timeSignature;
    }

    public function setTimeSignature(TimeSignature $timeSignature)
    {
        $this->timeSignature = $timeSignature;
    }

    public function getKey()
    {
        if ($this->key === null) {
          $this->key = new Key();
        }

        return $this->key;
    }

    public function setKey(Key $key)
    {
        $this->key = $key;
    }

    public function getTempo()
    {
        if ($this->tempo === null) {
          $this->tempo = new Tempo();
        }

        return $this->tempo;
    }

    public function setTempo(Tempo $tempo)
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

    public function getArtist()
    {
        return $this->artist;
    }

    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    public function getRaw()
    {
        return $this->raw;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    public function addRowBreak()
    {
        $this->rows[] = new Row();
    }

    public function addRow(Row $row = null)
    {
        if (is_null($row)) {
          $row = new Row();
        }
        $this->rows[] = $row;
        
        return new FluidObjectTraverser($row, $this);
    }
    
    public function getRows()
    {
        return $this->rows;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->rows);
    }
}
