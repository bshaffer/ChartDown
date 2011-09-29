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
 * Represents chart text.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class Text
{
  protected $text;
  protected $textile;
  
  public function __construct($text)
  {
    $this->text = $text;
    $this->textile = new \Textile();
  }
  
  public function __toString()
  {
    return $this->getRawText();
  }

  public function getRawText()
  {
      return $this->text;
  }
  
  public function getText()
  {
      return $this->textile->TextileThis($this->text);
  }
  
  public function addText($text)
  {
      $this->text .= "\n\n" . ($text instanceof self ? $text->getRawText() : $text);
  }
}
