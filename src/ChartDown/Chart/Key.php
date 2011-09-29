<?php

namespace ChartDown\Chart;

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
class Key
{
  protected $key;
  protected $mode;
  protected $accidental;
  
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
  
  const IONIAN     = 1;
  const DORIAN     = 2;
  const PHRYGIAN   = 3;
  const LYDIAN     = 4;
  const MIXOLYDIAN = 5;
  const AEOLIAN    = 6;
  const LOCRIAN    = 7;
  
  const SHARP        = 1;
  const FLAT         = -1;
  const DOUBLE_SHARP = +2;
  const DOUBLE_FLAT  = -2;
  
  public function __construct($key = null, $mode = 1, $accidental = null)
  {
    $this->key = $key;
    $this->mode = $mode;
    $this->accidental = $accidental;
  }
  
  public function __toString()
  {
    return $this->getKeyName() . $this->getAccidentalName() . $this->getModeName();
  }
  
  public function getKey()
  {
    return $this->key;
  }
  
  public function getMode()
  {
    return $this->mode;
  }
  
  public function getAccidental()
  {
    return $this->accidental;
  }

  public function getKeyName()
  {
    switch ($this->key) {
      case self::A:
        return 'A';

      case self::B:
        return 'B';

      case self::C:
        return 'C';

      case self::D:
        return 'D';

      case self::E:
        return 'E';

      case self::F:
        return 'F';

      case self::G:
        return 'G';
    }
    
    // Return most common accidentals
    switch ($this->key) {
      case self::G_SHARP:
      case self::A_FLAT:
        return 'Ab';

      case self::A_SHARP:
      case self::B_FLAT:
        return 'Bb';

      case self::C_SHARP:
      case self::D_FLAT:
        return 'C#';

      case self::D_SHARP:
      case self::E_FLAT:
        return 'Eb';

      case self::F:
      case self::G_FLAT:
        return 'F#';
    }
    
    return $this->key;
  }
  
  public function getModeName()
  {
    return $this->mode == self::AEOLIAN ? 'm' : '';
  }
  
  public function getAccidentalName()
  {
    switch ($this->accidental) {
      case self::SHARP:
        return '#';

      case self::DOUBLE_SHARP:
        return 'x';

      case self::FLAT:
        return 'b';

      case self::DOUBLE_FLAT:
        return 'bb';
    }

    return '';
  }
}
