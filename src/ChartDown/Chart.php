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
  protected $env;

  public function __construct($env, $options = array())
  {
    $this->env = $env;
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
      $this->bar = new ChartDown_Chart_Bar();
    } else {
      $this->bars[] = $bar;
    }

    return $this;
  }

  public function end()
  {
    if ($this->bar) {
      $this->bars[] = $this->bar;
      $this->bar = null;
    } else {
      throw new Exception('You must start a bar before ending one');
    }

    return $this;
  }

  public function addChord($value)
  {
    $this->bar->addChord(new ChartDown_Chart_Chord($value));

    return $this;
  }

  public function addLyric($value)
  {
    $this->bar->addLyric(new ChartDown_Chart_Lyric($value));

    return $this;
  }

  public function setLabel($value, $options = array())
  {
    $this->bar->setLabel(new ChartDown_Chart_Label($value, $options));

    return $this;
  }

  public function setBars($bars)
  {
    $this->bars = $bars;
  }

  public function getBars()
  {
    return $this->bars;
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
