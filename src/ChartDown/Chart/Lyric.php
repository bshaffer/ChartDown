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
 * Represents a chart lyric.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Lyric
{
  protected $lyric;
  
  public function __construct($lyric)
  {
    $this->lyric = $lyric;
  }
  
  public function __toString()
  {
    return (string) $this->lyric;
  }
}
