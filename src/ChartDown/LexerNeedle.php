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
 * @author    Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_LexerNeedle
{
    protected $text;
    protected $cursor;
    protected $end;
    protected $lineno;

    public function __construct($text, $cursor = 0)
    {
        $this->text     = str_replace(array("\r\n", "\r"), "\n", $text);
        $this->end        = strlen($this->text);
        $this->cursor = $cursor;
    }

    public function __toString()
    {
        return (string) $this->text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getTextAtCursor()
    {
        return substr($this->text, $this->cursor);
    }
    
    public function getCursor()
    {
        return $this->cursor;
    }

    public function rest()
    {
        $rest = substr($this->text, $this->cursor);
        $this->moveToEnd();

        return $rest;
    }

    public function moveToEnd()
    {
        $this->cursor = $this->end;
    }

    public function match($regex, $returnSubpatterns = false)
    {
        if(preg_match($regex, $this->text, $match, null, $this->cursor))
        {
            if ($returnSubpatterns) {
                    return $match;
            }
            
            return count($match) > 2 ? array_slice($match, 1) : array_pop($match);
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

    public function hasNext($text)
    {
            return $this->next($text) !== false;
    }

    public function nextMatch($regex)
    {
        preg_match($regex, $this->text, $match, null, $this->cursor);

        return $match ? strpos($this->text, $match[0], $this->cursor) : false;
    }

    public function moveToFirst($searches)
    {
        $pos = $this->end;
        $token = null;

        foreach ($searches as $search) {
                $tmpPos = $this->isRegex($search) ? $this->nextMatch($search) : $this->next($search);

                if (false !== $tmpPos && $tmpPos < $pos) {
                        $pos = $tmpPos;
                        $token = $search;
                }
        }

        return $pos === $this->end ? false : array($token, $this->moveTo($token));
    }

    public function moveTo($search)
    {
        if ($this->isRegex($search)) {
                $match = $this->match($search, true);
                $pos     = $this->next($match[0]) + strlen($match[0]);
        } elseif(is_string($search)) {
                $pos = $this->next($search) + strlen($search);
        } else {
                $pos = $search;
        }

        $text = substr($this->text, $this->cursor, $pos - $this->cursor);

        $this->moveCursor($text);

        return $text;
    }

    public function isRegex($text)
    {
        return strpos($text, '/') === 0 && strpos(strrev($text), '/') === 0;
    }

    public function getNext($text)
    {
        if(false !== ($pos = strpos($this->text, $text, $this->cursor)))
        {
            $prev = substr($this->text, $this->cursor, $pos - $this->cursor);
            $this->moveTo($text);

            return $prev;
        }

        return false;
    }

    public function isCurrentCharacter($string)
    {
        return false !== strpos($string, $this->text[$this->cursor]);
    }

    public function increment($step = 1)
    {
        $this->cursor += $step;
    }

    public function moveCursor($text)
    {
        $this->cursor += strlen($text);
        $this->lineno += substr_count($text, "\n");
    }

    public function isEOF()
    {
        return $this->cursor >= $this->end;
    }

    public function split($separator)
    {
        $needles = array();

        foreach (explode($separator, $this->text) as $part) {
                $needles[] = new self($part);
        }

        return $needles;
    }

    public function getNextLine()
    {
        return $this->isEOF() ? null : new self($this->hasNext("\n") ? $this->getNext("\n") : $this->rest());
    }
    
    public function getNumLines()
    {
        return substr_count(substr($this->text, $this->cursor), "\n") + 1;
    }

    public function ltrim($text)
    {
        if (0 === strpos($this->text, $text)) {
             $this->text = substr($this->text, strlen($text)); 
        }
            
        return $this;
    }

    public function rtrim($text)
    {
        if (strlen($this->text) -1 === ($pos = strrpos($this->text, $text))) {
             $this->text = substr($this->text, 0, $pos); 
        }
        
        return $this;
    }
        
    public function regexTrim($regex, $sep = '')
    {
        $match = $this->match($regex);
        
        if (is_array($match)) {
            $match = implode($sep, $match);
        }

        if ($match) {
                $this->text = $match;
                $this->pos = 0;
        }
    }

    public function trim()
    {
        $text = ltrim($this->text);
        $this->end -= (strlen($this->text) - strlen($text));
        $this->text = $text;
        return $this;
    }
}
