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
 * Represents a chart key.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Tempo
{
  protected $tempo;

  public function __construct($tempo = 120)
  {
    $this->tempo = $tempo;
  }
  
  public function __toString()
  {
    return (string) $this->tempo;
  }
  
  public function getTempo()
  {
    return $this->tempo;
  }
}
