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
 * Easy way to navigate text
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_LexerNeedle
{
  protected $text;
  protected $cursor;
  protected $end;
  protected $lineno;

  public function __construct($text, $cursor = 0)
  {
    $this->text   = $text;
    $this->end    = strlen($this->text);
    $this->cursor = $cursor;
  }
  
  public function match($regex)
  {
    if(preg_match($regex, $this->text, $match, null, $this->cursor))
    {
      $this->moveCursor($match[0]);
      
      return $match[0];
    }
  }

  public function matches($regex)
  {
    return preg_match($regex, $this->text, $match, null, $this->cursor);
  }

  public function next($text)
  {
    return strpos($this->text, $text, $this->cursor);
  }
  
  public function moveNext($text)
  {
    $pos = strpos($this->text, $text, $this->cursor);
    $prev = substr($this->text, $this->cursor, $pos);
    $this->moveCursor($prev);
    
    return $prev;
  }
  
  public function isCurrentCharacter($string)
  {
    return false !== strpos($string, $this->text[$this->cursor]);
  }
  
  public function increment($step = 1)
  {
    $this->cursor += $step;
  }

  protected function moveCursor($text)
  {
      $this->cursor += strlen($text);
      $this->lineno += substr_count($text, "\n");
  }
  
  public function isEOF()
  {
    return $this->cursor >= $this->end;
  }
}
