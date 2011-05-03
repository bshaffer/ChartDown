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
 * Represents a chart time signature.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_TimeSignature
{
  protected $beatsPerMeasure;
  protected $beatNoteValue;

  public function __construct($beatsPerMeasure, $beatNoteValue)
  {
    $this->beatsPerMeasure = $beatsPerMeasure;
    $this->beatNoteValue   = $beatNoteValue;
  }
  
  public function __toString()
  {
    return $this->beatsPerMeasure && $this->beatNoteValue ? sprintf('%s/%s', $this->beatsPerMeasure, $this->beatNoteValue) : '';
  }
}
