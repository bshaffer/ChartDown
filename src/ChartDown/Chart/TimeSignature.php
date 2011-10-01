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
 * Represents a chart time signature.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class TimeSignature
{
  protected $beatsPerMeasure;
  protected $beatNoteValue;

  public function __construct($beatsPerMeasure = 4, $beatNoteValue = 4)
  {
    $this->beatsPerMeasure = $beatsPerMeasure;
    $this->beatNoteValue   = $beatNoteValue;
  }
  
  public function __toString()
  {
    return $this->beatsPerMeasure && $this->beatNoteValue ? sprintf('%s/%s', $this->beatsPerMeasure, $this->beatNoteValue) : '';
  }
}
