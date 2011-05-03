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
 * Represents a chart chord.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Chord
{
  const A                = 1;
  const A_SHARP          = 2;
  const B_FLAT           = 2;
  const B                = 3;
  const C_FLAT           = 3;
  const B_SHARP          = 4;
  const C                = 4;
  const C_SHARP          = 5;
  const D_FLAT           = 5;
  const D                = 6;
  const D_SHARP          = 7;
  const E_FLAT           = 7;
  const E                = 8;
  const F_FLAT           = 8;
  const E_SHARP          = 9;
  const F                = 9;
  const F_SHARP          = 10;
  const G_FLAT           = 10;
  const G                = 11;
  const G_SHARP          = 12;
  const A_FLAT           = 12;
  const NASHVILLE_NUMBER = null;

  protected $name;

  public function __construct($name)
  {
    $this->name = $name;
  }
  
  public function __toString()
  {
    return $this->name;
  }
}
